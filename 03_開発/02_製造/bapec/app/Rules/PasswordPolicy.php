<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Helpers\Util\SystemHelper;

/**
 * パスワードポリシーのルール定義
 */
class PasswordPolicy implements Rule
{
    /**
     * コンストラクター
     */
    public function __construct()
    {
        $this->passwordPolicy = SystemHelper::getAppSettingValue('auth.password-policy');
    }

    /**
     * バリデーションの成功を判定
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$this->passwordPolicy) {
          return true;
        } elseif ($this->passwordPolicy['minlength'] > 0 && strlen($value) < $this->passwordPolicy['minlength']) {
          $this->error = 'minlength';
          return false;
        } elseif ($this->passwordPolicy['include-numeric'] && !preg_match("/[0-9]/", $value)) {
          $this->error = 'include-numeric';
          return false;
        } elseif ($this->passwordPolicy['include-alphabet'] && !preg_match("/[a-z]/i", $value)) {
          $this->error = 'include-alphabet';
          return false;
        } elseif ($this->passwordPolicy['include-symbol'] && !preg_match("/[".preg_quote("!#$%()*+-./:;=?@[]^_`{|}","/")."]+/", $value)) {
          $this->error = 'include-symbol';
          return false;
        } elseif ($this->passwordPolicy['minlength'] > 0 && $this->passwordPolicy['frequently-phrases']) {
          if (!isset($this->passwordPolicy['frequently-phrases'][$this->passwordPolicy['minlength']])) {
            return true;
          }
          $phrases = $this->passwordPolicy['frequently-phrases'][$this->passwordPolicy['minlength']];
          if (in_array($value, $phrases)) {
            $this->error = 'frequently-phrases';
            return false;
          }
          return true;
        }

        return true;
    }

    /**
     * バリデーションエラーメッセージの取得
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.password-policy.'.$this->error, ['min' => $this->passwordPolicy['minlength']]);
    }
}