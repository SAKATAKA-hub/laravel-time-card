<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

use App\Http\ViewComposers\S3ImageUrlComposer;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','email','image','comment','password','app_dministrator','easy_user',
    ];




    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * $user->open_post
     * ユーザーの公開中投稿数を表示
     *
     *
     * @return String
     */
    public function getOpenPostAttribute()
    {
        return Note::publicationOrderMypageNotes($this)->count();
    }




    /**
     * $user->private_post
     * ユーザーの非公開投稿数を表示
     *
     *
     * @return String
     */
    public function getPrivatePostAttribute()
    {
        return Note::unpublishedOrderMypageNotes($this)->count();
    }




    /**
     * $user->replace_comment
     * 'comment'カラムの表示に'改行'を反映させる
     *
     *
     * @return String
     */
    public function getReplaceCommentAttribute()
    {
        $value = e($this->comment);
        $value = nl2br($value);

        return $value;
    }




    /**
     * $user->image_url
     * S3に保存されたユーザー画像のURLを表示
     * 画像の登録がない場合は、'no-image'の画像を表示(パスはS3ImageUrlComposerより参照)
     *
     *
     * @return String
     */
    public function getImageUrlAttribute()
    {
        $path ='';
        $no_image = 'common/img/no_img.png'; //ユーザー画像の登録なしのパス

        // ユーザー画像が登録されていないとき、
        if(
            empty($this->image) or !Storage::exists($this->image)
        ){
            $path = asset('storage/'.$no_image);
        }
        // 登録しているユーザー画像の表示
        else
        {
            $path = asset('storage/'.$this->image);
        }


        return $path;
    }

}
