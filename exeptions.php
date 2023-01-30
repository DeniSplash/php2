<?php

class MyErrorExeption extends Exception {}
class HttpErrorExeption extends Exception {}
function foo()
{
    try {

        throw new HttpErrorExeption("error");

    } catch (MyErrorExeption $exeption) {
        echo $exeption->getMessage();
        return false;
    }

    //return true;
}

try {
    var_dump(foo());
} catch (HttpErrorExeption $exeption) {
    echo $exeption->getMessage();
}
catch (Exception $exeption) {
    echo 'Все ошибки';
}