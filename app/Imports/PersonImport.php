<?php

namespace App\Imports;

use App\Models\People;
use App\Models\Person;
use Maatwebsite\Excel\Concerns\ToModel;

class PersonImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $duplicate = Person::where('name', $row['name'])
            ->where('other_names', $row['other_names'])
            ->where('age', $row['age'])
            ->first();

        if ($duplicate) {
            return null;
        }
        return new Person([
            //
            'name' => $row['name'],
            'other_names' => $row['other_names'],
            'sex' => $row['sex'],
            'dob' => $row['dob'],
            'email' => $row['email'],
            'age' => $row['age'],
            'district_of_origin' => $row['district_of_origin'],
            'marital_status' => $row['marital_status'],
            'religion' => $row['religion']

        ]);



        /*
        
Full texts
id Descending 1	
created_at	
updated_at	
name	
address	
phone_number	
email	
phone_number_2	
dob	
sex	
photo	
district_of_origin	
other_names	
id_number	
ethnicity	
marital_status	
religion	
place_of_birth	
birth_hospital	
birth_no_hospital_description	
languages	
employer	
position	
year_of_employment	
district_id	
opd_id	
aspirations	
skills	
is_formal_education	
is_employed	
is_member	
select_opd_or_du	
is_same_address	
is_formerly_employed	
is_approved	
profiler	
registration_method	
TransactionId	
disability	
district_search	
education_level	
is_verified	
indicate_class	
field_of_study	
occupation	
informal_education	
sub_county	
village	
employment_status	
district_of_residence	
age
        */
    }
}
