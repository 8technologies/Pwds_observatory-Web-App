<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Disability;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PWDProfileController extends Controller
{
    /**
     * Get PWD profiles with pagination and search
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Person::query();
            
            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', $searchTerm)
                      ->orWhere('other_names', 'LIKE', $searchTerm)
                      ->orWhere('phone_number', 'LIKE', $searchTerm)
                      ->orWhere('email', 'LIKE', $searchTerm)
                      ->orWhere('village', 'LIKE', $searchTerm)
                      ->orWhere('sub_county', 'LIKE', $searchTerm);
                });
            }
            
            // Filter by disability
            if ($request->has('disability_id') && !empty($request->disability_id)) {
                $query->whereRaw('FIND_IN_SET(?, disabilities)', [$request->disability_id]);
            }
            
            // Filter by gender
            if ($request->has('sex') && !empty($request->sex)) {
                $query->where('sex', $request->sex);
            }
            
            // Filter by age range
            if ($request->has('min_age') && !empty($request->min_age)) {
                $query->where('age', '>=', $request->min_age);
            }
            if ($request->has('max_age') && !empty($request->max_age)) {
                $query->where('age', '<=', $request->max_age);
            }
            
            // Filter by location
            if ($request->has('sub_county') && !empty($request->sub_county)) {
                $query->where('sub_county', 'LIKE', '%' . $request->sub_county . '%');
            }
            
            // Order by
            $orderBy = $request->get('order_by', 'created_at');
            $orderDirection = $request->get('order_direction', 'desc');
            $query->orderBy($orderBy, $orderDirection);
            
            // Pagination
            $perPage = min($request->get('per_page', 20), 100); // Max 100 items per page
            $profiles = $query->paginate($perPage);
            
            // Format the response
            $formattedProfiles = $profiles->getCollection()->map(function($profile) {
                return $this->formatProfileForApi($profile);
            });
            
            return response()->json([
                'success' => true,
                'data' => $formattedProfiles,
                'pagination' => [
                    'current_page' => $profiles->currentPage(),
                    'last_page' => $profiles->lastPage(),
                    'per_page' => $profiles->perPage(),
                    'total' => $profiles->total(),
                    'from' => $profiles->firstItem(),
                    'to' => $profiles->lastItem(),
                ],
                'filters' => [
                    'search' => $request->search,
                    'disability_id' => $request->disability_id,
                    'sex' => $request->sex,
                    'min_age' => $request->min_age,
                    'max_age' => $request->max_age,
                    'sub_county' => $request->sub_county,
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('PWD Profile Index Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profiles',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Get statistics for PWD profiles
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total_profiles' => Person::count(),
                'male_count' => Person::where('sex', 'Male')->count(),
                'female_count' => Person::where('sex', 'Female')->count(),
                'profiles_today' => Person::whereDate('created_at', today())->count(),
                'profiles_this_week' => Person::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                'profiles_this_month' => Person::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'average_age' => round(Person::where('age', '>', 0)->avg('age'), 1),
                'age_distribution' => [
                    '0-18' => Person::whereBetween('age', [0, 18])->count(),
                    '19-35' => Person::whereBetween('age', [19, 35])->count(),
                    '36-60' => Person::whereBetween('age', [36, 60])->count(),
                    '60+' => Person::where('age', '>', 60)->count(),
                ],
                'disability_distribution' => $this->getDisabilityDistribution(),
                'location_distribution' => Person::selectRaw('sub_county, COUNT(*) as count')
                    ->whereNotNull('sub_county')
                    ->where('sub_county', '!=', '')
                    ->groupBy('sub_county')
                    ->orderByDesc('count')
                    ->limit(10)
                    ->pluck('count', 'sub_county'),
                'recent_profiles' => Person::orderByDesc('created_at')
                    ->limit(5)
                    ->get()
                    ->map(function($profile) {
                        return [
                            'id' => $profile->id,
                            'name' => trim($profile->other_names . ' ' . $profile->name),
                            'created_at' => $profile->created_at->format('Y-m-d H:i:s'),
                        ];
                    }),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
            
        } catch (\Exception $e) {
            Log::error('PWD Profile Statistics Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Show a specific PWD profile
     */
    public function show($id): JsonResponse
    {
        try {
            $profile = Person::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $this->formatProfileForApi($profile, true), // Detailed view
            ]);
            
        } catch (\Exception $e) {
            Log::error('PWD Profile Show Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Profile not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
    
    /**
     * Store a new PWD profile
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = $this->validateProfileData($request);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }
            
            $data = $validator->validated();
            
            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photoPath = $this->handlePhotoUpload($request->file('photo'));
                $data['photo'] = $photoPath;
            } elseif ($request->has('photo_base64') && !empty($request->photo_base64)) {
                $photoPath = $this->handleBase64Photo($request->photo_base64);
                $data['photo'] = $photoPath;
            }
            
            // Create the profile
            $profile = Person::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Profile created successfully',
                'data' => $this->formatProfileForApi($profile, true),
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('PWD Profile Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Update a PWD profile
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $profile = Person::findOrFail($id);
            
            $validator = $this->validateProfileData($request, $profile);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }
            
            $data = $validator->validated();
            
            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($profile->photo && Storage::disk('public')->exists($profile->photo)) {
                    Storage::disk('public')->delete($profile->photo);
                }
                
                $photoPath = $this->handlePhotoUpload($request->file('photo'));
                $data['photo'] = $photoPath;
            } elseif ($request->has('photo_base64') && !empty($request->photo_base64)) {
                // Delete old photo if exists
                if ($profile->photo && Storage::disk('public')->exists($profile->photo)) {
                    Storage::disk('public')->delete($profile->photo);
                }
                
                $photoPath = $this->handleBase64Photo($request->photo_base64);
                $data['photo'] = $photoPath;
            } elseif ($request->has('remove_photo') && $request->remove_photo == '1') {
                // Remove photo
                if ($profile->photo && Storage::disk('public')->exists($profile->photo)) {
                    Storage::disk('public')->delete($profile->photo);
                }
                $data['photo'] = null;
            }
            
            // Update the profile
            $profile->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $this->formatProfileForApi($profile, true),
            ]);
            
        } catch (\Exception $e) {
            Log::error('PWD Profile Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Delete a PWD profile
     */
    public function destroy($id): JsonResponse
    {
        try {
            $profile = Person::findOrFail($id);
            
            // Delete photo if exists
            if ($profile->photo && Storage::disk('public')->exists($profile->photo)) {
                Storage::disk('public')->delete($profile->photo);
            }
            
            $profile->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Profile deleted successfully',
            ]);
            
        } catch (\Exception $e) {
            Log::error('PWD Profile Delete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Bulk sync profiles from mobile app
     */
    public function bulkSync(Request $request): JsonResponse
    {
        try {
            $profiles = $request->get('profiles', []);
            $results = [];
            
            foreach ($profiles as $profileData) {
                try {
                    $validator = Validator::make($profileData, $this->getValidationRules());
                    
                    if ($validator->fails()) {
                        $results[] = [
                            'local_id' => $profileData['local_id'] ?? null,
                            'success' => false,
                            'message' => 'Validation failed',
                            'errors' => $validator->errors(),
                        ];
                        continue;
                    }
                    
                    $data = $validator->validated();
                    unset($data['local_id']); // Remove local_id from data to save
                    
                    // Handle base64 photo
                    if (isset($profileData['photo_base64']) && !empty($profileData['photo_base64'])) {
                        $photoPath = $this->handleBase64Photo($profileData['photo_base64']);
                        $data['photo'] = $photoPath;
                    }
                    
                    // Check if profile already exists (by phone or email)
                    $existingProfile = null;
                    if (!empty($data['phone_number'])) {
                        $existingProfile = Person::where('phone_number', $data['phone_number'])->first();
                    }
                    if (!$existingProfile && !empty($data['email'])) {
                        $existingProfile = Person::where('email', $data['email'])->first();
                    }
                    
                    if ($existingProfile) {
                        // Update existing profile
                        $existingProfile->update($data);
                        $profile = $existingProfile;
                        $action = 'updated';
                    } else {
                        // Create new profile
                        $profile = Person::create($data);
                        $action = 'created';
                    }
                    
                    $results[] = [
                        'local_id' => $profileData['local_id'] ?? null,
                        'server_id' => $profile->id,
                        'success' => true,
                        'action' => $action,
                        'message' => "Profile {$action} successfully",
                    ];
                    
                } catch (\Exception $e) {
                    $results[] = [
                        'local_id' => $profileData['local_id'] ?? null,
                        'success' => false,
                        'message' => 'Failed to sync profile: ' . $e->getMessage(),
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Bulk sync completed',
                'results' => $results,
                'summary' => [
                    'total' => count($profiles),
                    'successful' => count(array_filter($results, fn($r) => $r['success'])),
                    'failed' => count(array_filter($results, fn($r) => !$r['success'])),
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('PWD Profile Bulk Sync Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Bulk sync failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Get available disabilities
     */
    public function getDisabilities(): JsonResponse
    {
        try {
            $disabilities = Disability::all()->map(function($disability) {
                return [
                    'id' => $disability->id,
                    'name' => $disability->name,
                    'description' => $disability->description ?? '',
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $disabilities,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get Disabilities Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch disabilities',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Format profile data for API response
     */
    private function formatProfileForApi($profile, $detailed = false): array
    {
        $data = [
            'id' => $profile->id,
            'name' => $profile->name,
            'other_names' => $profile->other_names,
            'full_name' => trim($profile->other_names . ' ' . $profile->name),
            'sex' => $profile->sex,
            'age' => $profile->age,
            'dob' => $profile->dob,
            'phone_number' => $profile->phone_number,
            'email' => $profile->email,
            'photo' => $profile->photo ? asset('storage/' . $profile->photo) : null,
            'disabilities' => $profile->disabilities,
            'disabilities_text' => $this->getDisabilitiesText($profile->disabilities),
            'village' => $profile->village,
            'sub_county' => $profile->sub_county,
            'created_at' => $profile->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $profile->updated_at->format('Y-m-d H:i:s'),
        ];
        
        if ($detailed) {
            $data = array_merge($data, [
                'phone_number_2' => $profile->phone_number_2,
                'address' => $profile->address,
                'district_id' => $profile->district_id,
                'id_number' => $profile->id_number,
                'id_type' => $profile->id_type,
                'ethnicity' => $profile->ethnicity,
                'marital_status' => $profile->marital_status,
                'religion' => $profile->religion,
                'education_level' => $profile->education_level,
                'is_formal_education' => $profile->is_formal_education,
                'informal_education' => $profile->informal_education,
                'is_employed' => $profile->is_employed,
                'employment_status' => $profile->employment_status,
                'employer' => $profile->employer,
                'position' => $profile->position,
                'occupation' => $profile->occupation,
                'aspirations' => $profile->aspirations,
                'skills' => $profile->skills,
                'next_of_kin_name' => $profile->next_of_kin_name,
                'next_of_kin_phone' => $profile->next_of_kin_phone,
                'next_of_kin_relationship' => $profile->next_of_kin_relationship,
                'next_of_kin_email' => $profile->next_of_kin_email,
                'next_of_kin_address' => $profile->next_of_kin_address,
                'profiler' => $profile->profiler,
                'registration_date' => $profile->registration_date,
            ]);
        }
        
        return $data;
    }
    
    /**
     * Get validation rules for profile data
     */
    private function getValidationRules($profile = null): array
    {
        $phoneRule = 'required|string|regex:/^0[7-9][0-9]{8}$/';
        if ($profile) {
            $phoneRule .= '|unique:people,phone_number,' . $profile->id;
        } else {
            $phoneRule .= '|unique:people,phone_number';
        }
        
        return [
            'name' => 'required|string|max:255',
            'other_names' => 'required|string|max:255',
            'sex' => 'required|in:Male,Female',
            'age' => 'required|integer|min:1|max:120',
            'dob' => 'nullable|date|before:today',
            'phone_number' => $phoneRule,
            'phone_number_2' => 'nullable|string|regex:/^0[7-9][0-9]{8}$/',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'district_id' => 'nullable|string|max:255',
            'sub_county' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'id_number' => 'nullable|string|max:255',
            'id_type' => 'nullable|string|max:255',
            'ethnicity' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'education_level' => 'nullable|string|max:255',
            'is_formal_education' => 'nullable|string|max:255',
            'informal_education' => 'nullable|string|max:500',
            'is_employed' => 'nullable|string|max:255',
            'employment_status' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'aspirations' => 'nullable|string|max:1000',
            'skills' => 'nullable|string|max:1000',
            'disabilities' => 'nullable|string|max:255',
            'next_of_kin_name' => 'nullable|string|max:255',
            'next_of_kin_phone' => 'nullable|string|max:255',
            'next_of_kin_relationship' => 'nullable|string|max:255',
            'next_of_kin_email' => 'nullable|email|max:255',
            'next_of_kin_address' => 'nullable|string|max:500',
            'profiler' => 'nullable|string|max:255',
            'registration_date' => 'nullable|date',
            'local_id' => 'nullable|integer', // For bulk sync
            'photo_base64' => 'nullable|string', // For base64 photo upload
        ];
    }
    
    /**
     * Validate profile data
     */
    private function validateProfileData(Request $request, $profile = null)
    {
        return Validator::make($request->all(), $this->getValidationRules($profile));
    }
    
    /**
     * Handle photo upload
     */
    private function handlePhotoUpload($photo): string
    {
        $fileName = 'pwd_' . Str::random(10) . '_' . time() . '.' . $photo->getClientOriginalExtension();
        return $photo->storeAs('pwd_photos', $fileName, 'public');
    }
    
    /**
     * Handle base64 photo upload
     */
    private function handleBase64Photo($base64Photo): string
    {
        // Remove data:image/...;base64, prefix if present
        if (strpos($base64Photo, 'data:image/') === 0) {
            $base64Photo = substr($base64Photo, strpos($base64Photo, ',') + 1);
        }
        
        $imageData = base64_decode($base64Photo);
        $fileName = 'pwd_' . Str::random(10) . '_' . time() . '.jpg';
        $filePath = 'pwd_photos/' . $fileName;
        
        Storage::disk('public')->put($filePath, $imageData);
        
        return $filePath;
    }
    
    /**
     * Get disabilities text from IDs
     */
    private function getDisabilitiesText($disabilityIds): string
    {
        if (empty($disabilityIds)) {
            return '';
        }
        
        $ids = explode(',', $disabilityIds);
        $disabilities = Disability::whereIn('id', $ids)->pluck('name')->toArray();
        
        return implode(', ', $disabilities);
    }
    
    /**
     * Get disability distribution for statistics
     */
    private function getDisabilityDistribution(): array
    {
        $distribution = [];
        $disabilities = Disability::all();
        
        foreach ($disabilities as $disability) {
            $count = Person::whereRaw('FIND_IN_SET(?, disabilities)', [$disability->id])->count();
            $distribution[$disability->name] = $count;
        }
        
        return $distribution;
    }
}
