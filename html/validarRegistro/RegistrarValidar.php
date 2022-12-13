<?php
namespace validarRegistro;


/**
 * Clase RegistrarValidar
 * 
 * Contiene funciones que validan ciertos datos del registro de cuentas.
 * 
 * @author Adrian Fernandez Perez y Jorge Val Gil
 * @version 1.0
 */
class RegistrarValidar {

    /**
     * Función compare_password
     * 
     * Le pasamos por parametro las dos contraseñas a comparar.
     * Si las dos contraseñas son iguales, llamará a la funcion hash_pass_register
     * para hashearla y la devuelve.
     * Si no, devuelve false.
     * 
     * @see hash_pass_register()
     * 
     * @param mixed $pass1 Primera contraseña
     * @param mixed $pass2 Segunda contraseña
     * 
     * @return mixed si todo fue bien devuelve la contraseña hasheada. si no devuelve false
     */
    function compare_password($pass1, $pass2) {
        if ($pass1 === $pass2) {
            $password = $this->hash_pass_register($pass1);
            return $password;
        } else {
            return false;
        }
    }


    /**
     * Función hash_pass_register
     * 
     * Guarda en una variable, la contraseña pasada por parametro hasheada y la devuelve.
     * 
     * @param mixed $password Contraseña que se le pasa para hashearla.
     * 
     * @return string Devuelve la contraseña hasheada.
     */
    function hash_pass_register($password) {

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        return $password_hash;
    }



    /**
     * Función valid_email
     * 
     * Recibe un email, guarda en una variable ese correo validado y lo devuelve o da error.
     * 
     * @param mixed $email email que le pasamos como parámetro para validar.
     * 
     * @return mixed Devuelve true si el correo es valido, si no, devuelve false.
     */
    function valid_email($email) {
        $valid = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$valid) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Función valid_phone
     * 
     * Primero comprueba si el teléfono tiene espacios, se los quita y guarda el valor en una variable.
     * Comprueba si el teléfono tiene un tamaño de 9 dígitos y si es numérico, si eas así devuelve true, si no, devuelve false.
     * 
     * @param mixed $phone_number teléfono que le pasamos como parámetro para validar.
     * 
     * @return mixed Devuelve true si el teléfono es valido, si no, devuelve false.
     */
    function valid_phone($phone_number) {
        $phone = str_replace(' ', '', $phone_number);
        if (strlen($phone) == 9 && is_numeric($phone)) {
            return true;
        } else {
            return false;
        }
    }

}
