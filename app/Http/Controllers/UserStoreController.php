<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//TODO: Cada clase debe ir en archivos separados



//Principio de responsabildiad unica (S)
//MAL
class UserStoreController1 extends Controller
{
    public function store(Request $user){
        if(Auth::id() != '123'){
            $user = new User();
            $user->password = self::passwordHash($user->password);
            $user->save();
        }else{
            throw new Exception("The User can't store Users", 403);
        }
    }

    public function passwordHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

}

//Principio de responsabildiad unica (S)
// BIEN
class UserStoreController2 extends Controller
{
    public function store(Request $user){
        (new UserValidatorAuth)->validateUser();
        $user = new User();
        $user->password = (new UserStoreHelper)->passwordHash($user->password);
        $user->save();
    }
}


class UserValidatorAuth {
    public function validateUser(){
        if(Auth::id() != '123'){
            throw new Exception("The User can't store Users", 403);
        }
    }
}

class UserStoreHelper{
    public function passwordHash($data){
        return password_hash($data, PASSWORD_DEFAULT);
    }
}


// Principio Abierto Cerrado
// MAL
class Users{
    public function identifyUser($user){
        switch($user){
            case 'natural':
                $this->sayHello('natural user');
            break;
            case 'bussines':
                $this->sayHello('bussines user');
            break;
            case 'admin':
                $this->sayHello('admin user');
            break;
        }
    }

    public function sayHello($user){
        return 'Hello word from ' . $user;
    }
}

// Principio Abierto Cerrado
// Bien
// Las interfaces permiten que esten abiertas a la implementacion y cerradas a la modificacion
interface IUsers
{
    public function sayHello();
}

class NaturalUser implements IUsers
{
    public function sayHello()
    {
        return 'Hello word from NaturalUser';
    }
}

class BussinesUser implements IUsers
{
    public function sayHello(){
        return 'Hello word from BussinesUser';

    }
}

class AdminUser implements IUsers
{
    public function sayHello(){
        return 'Hello word from AdminUser';

    }
}


class OtroUser implements IUsers
{
    public function sayHello(){
        return 'Hello word from OtroUser';

    }
}

class UsersImplementation
{
    public function identifyUser(IUsers $users)
    {
        $users->sayHello();
    }
}


// L Principio de sustitucion de Liskov
// tipar el codigo te permite mutar las clases hijas
abstract class HelloClass
{
    abstract function sayHello(string $name) : string|array|int;

    public function sayHi(string $name) : string
    {
        return 'Hi ' . $name;
    }
}

abstract class NotImplementedClass
{
    abstract function lala() : void;
}

interface BayInterface
{
    public function sayBye(string $name) : string;
}

interface  ConversationInterface
{
    public function getConversation() : void;
}

final class ChildfFinalClass extends HelloClass implements BayInterface, ConversationInterface{
    function sayHello(string $name) : string
    {
        return 'Hello Word';
    }

    public function sayBye(string $name): string
    {
        return 'Bye ' . $name;
    }

    public function getConversation() : void{
        echo 'bla, bla, bla';
    }
}

final class ChildfFinalClass2 extends HelloClass{
    function sayHello(string $name) : string
    {
        return 'Hello ' . $name;
    }
}


/*
Clase Abstracta	Interfaz
clase       La palabra clave abstract se usa para crear una clase abstracta y se puede usar con métodos. (a menos uno debe ser 100% abstracto)
interfaz    La palabra clave de interface se usa para crear una interfaz, pero no se puede usar con métodos.

clase       Una clase puede extender solo una clase abstracta.
interfaz    Una clase puede implementar más de una interfaz.

clase       Las variables no son definitivas por defecto. Puede contener variables no finales.
interfaz    Las variables son finales por defecto en una interfaz.

clase       Una clase abstracta puede proporcionar la implementación de una interfaz.
interfaz    Una interfaz no puede proporcionar la implementación de una clase abstracta.

clase       Puede tener métodos con implementaciones.
interfaz    Proporciona una abstracción absoluta y no puede tener implementaciones de métodos.

clase       Puede tener modificadores de acceso públicos, privados, estáticos y protegidos.
interfaz    Los métodos son implícitamente públicos y abstractos en la interfaz de Java.

clase       No admite herencias múltiples.
interfaz    Es compatible con herencias múltiples.

clase       Es ideal para la reutilización del código y la perspectiva de la evolución.
interfaz    Es ideal para la declaración de tipo.
*/


// (I) Principio de segregacion de interfaces: No se debe sobrecargar de metodos innecesarios las clases
// Tip: Una clase solo debe tener codigo que deba usar

interface IAnimalQueAnda
{
    public function andar():void;
}

interface IAnimalQueCorre
{

    public function correr():void;
    public function trotar():void;

}

class Perro implements IAnimalQueAnda, IAnimalQueCorre
{
    public function andar() : void{
        echo 'Ando por aqui';
    }
    public function correr() : void{
        echo 'Soy perro y puedo correr ;-)';
    }
    public function trotar() : void{
        echo 'Soy perro y puedo correr ;-)';
    }
}

class Tortuga implements IAnimalQueAnda
{
    public function andar():void
    {
        echo ' Soy tortuga solo puedo andar ;-( ';
    }

}

/*
Aqui lo que debemos apreciar es que inicialmente podriamos tener una Clase Animal que tenga los metodos correr y andar
Pero estariamos sobre cargando ya que correr solo se debe implementar en la clase Perro no asi en Tortuga
Sin embargo si hacemos esto, el compilador te obligara a implementar correr en tortuga y pues lo implementaras aunque no lo uses solo para compilar
*/


/*
Inyeccion de dependencias:
Es una técnica en la cual en el constructor de una clase inyectamos las clases que vamos a usar en el scope o en nuestra clase
Esto reduce el acoplamiento de código (acoplamiento de codigo se refiere a que clase B conoce a clase A, cuando no fuera necesario)
Se usa cuando no se requiere herencia, si requiere herencia pues ni modo, B va depender de A
*/
class ClaseA
{
    public function getData() : array{
        return Array();
    }
}

// BAD
class ClaseB
{
    public function proccessData() : string
    {
        $claseA = new ClaseA();
        return 'Bad Procesing Data ' . $claseA->getData()[0];
    }
}

//Good
class ClaseC
{
    private $claseA;
    public function __construct(ClaseA $claseA)
    {
        $this->claseA = $claseA; // a partir de aqui ya puedo usar en caulquier metodo a ClassA
    }

    public function proccessData() : string
    {
        return 'Good Procesing Data ' . $this->claseA->getData()[0];
    }


}


/*
Inversion de dependencias:
La inversion de dependencias se encarga de la relacion entre dos clases por medio de una abstracción, la cual conecta ambas dependencias
*/

class BadIndexController
{
    public function __contruct()
    {

    }
    public function __invoke() : ?array //__invoke al invocar esta clase este metodo se ejecutara (similar a un run en un seeder)
    {
        $users = User::all(); // aqui usamos Users lo que nos acopla a la clase Models de eloquent
        return ['users' => $users];
    }
}

//
class EloquentAdapter implements ConnectAdapter
{ //esta clase dependera de Eloquent
    public function getAllUser() : ?array
    {
        $users = User::all();
        return ['users' => $users];
    }
}

class StoreProcedureAdapter implements ConnectAdapter
{
    public function getAllUser() : ?array
    {
        $users = DB::execute('CALL lsp_all_users');
        return ['users' => $users];
    }
}


interface ConnectAdapter
{
    public function getAllUser() : ?array;
}

class GoodIndexController
{
    private $adapter;
    public function __contruct(ConnectAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function __invoke() : ?array //__invoke al invocar esta clase este metodo se ejecutara (similar a un run en un seeder)
    {
        return $this->adapter->getAllUser();
    }
}
