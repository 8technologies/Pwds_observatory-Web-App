<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPost extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function created_by()
    { 
        $u = Administrator::find($this->user_id);
        if($u == null){
            $this->user_id = 1;
            $this->save();
        }
        return $this->belongsTo(Administrator::class, 'user_id');
    }

    public function getPostCategoryTextAttribute()
    {
        $d = PostCategory::find($this->post_category_id);
        if ($d == null) {
            return 'Not Subcounty.';
        }
        return $d->name;
    }
    protected $appends = ['post_category_text'];
}
