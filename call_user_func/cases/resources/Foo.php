<?php
class Foo {
    public function cd() {
        sprintf('%s %s %s', uniqid(), uniqid(), uniqid());
    }

    public function cuf() {
        call_user_func('sprintf', '%s %s %s', uniqid(), uniqid(), uniqid());
    }

    public function cufa() {
        call_user_func_array('sprintf', array('%s %s %s', uniqid(), uniqid(), uniqid()));
    }
}
