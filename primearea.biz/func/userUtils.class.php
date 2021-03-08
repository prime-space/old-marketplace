<?php
class userUtils{
    public $data;
    public $user;
    public $db;

    public function __construct()
    {
        global $db;
        $this->db = $db;
    }
    
    public function ajax($method)
    {
        global $data, $user;
    
        $this->data = $data;
        $this->user = $user;
    
        switch ($method) {
            case 'emailInforming':
                return $this->emailInforming();
            case 'twinAuthenticateSwitch':
                return $this->twinAuthenticateSwitch();
            case 'wish':
                return $this->wish();
            default:
                return array('status' => 'error', 'message' => 'Unknown method'.$method);
        }
    }
    
    private function emailInforming()
    {
        $informing = empty($this->data['value']) ? 0 : 1;

        $this->db->query("
            UPDATE user
            SET emailInforming = $informing
            WHERE id = {$this->user->id}
        ");

        return ['status' => 'ok'];
    }

    private function twinAuthenticateSwitch()
    {
        if ($this->user->googleSecret === '' && $this->data['action'] === 'on') {
            $action = 'on';
        } elseif ($this->user->googleSecret !== '' && $this->data['action'] === 'off') {
            $action = 'off';
        } else {
            return ['status' => 'error', 'list' => ['Невозможно ']];
        }

        $errors = [];

        if (auth::encodePassword($this->data['pass']) !== $this->user->pass) {
            $errors[] = 'Неверный код или пароль';
        } else {
            $secret = $action === 'on' ? $this->data['secret'] : $this->user->googleSecret;
            if (!preg_match('/^[A-Z0-9]{16}$/', $secret)) {
                $errors[] = 'Неверный формат секрета';
            } else {
                include "google-autenticator/GoogleAuthenticator.php";
                $googleAuthenticator = new GoogleAuthenticator;
                if ($googleAuthenticator->getCode($secret) !== $this->data['code']) {
                    $errors[] = 'Неверный код или пароль';
                }
            }
        }

        if (count($errors) > 0) {
            return ['status' => 'error', 'list' => $errors];
        }

        $value = $action === 'on' ? $secret : '';

        $value = $this->db->safesql($value);
        $this->db->query("
            UPDATE user
            SET googleSecret = '$value'
            WHERE id = {$this->user->id}
        ");

        return ['status' => 'ok'];
    }

    private function wish()
    {
        $errors = [];

        if (empty($this->data['text'])) {
            $errors[] = 'Введите текст';
        }

        if (count($errors) > 0) {
            return ['status' => 'error', 'list' => $errors];
        }

        $text = $this->db->safesql($this->data['text'], false, true);

        $this->db->query("
            INSERT INTO `wish`
                (`userId`, `text`)
            VALUES
                ({$this->user->id}, '$text')
        ");

        return ['status' => 'ok'];
    }
}
