<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table ="client";
    protected $primarykey = "idpersonne";
    public $timestamps = false;
    protected $filable = [
        'cpLivraison',
        'cpLivraison',
        'villeLivraison',
        'telephone',
        'paysLivraison',
        'nomcomplet',
        'rueLivraison'
    ];

    public function __construct($idpersonne,$cpLivraison,$villeLivraison,$telephone,$paysLivraison,$nomcomplet,$rueLivraison)
    {
        $this->idpersonne = $idpersonne;
        $this->cpLivraison = $cpLivraison;
        $this->villeLivraison = $villeLivraison;
        $this->telephone = $telephone;
        $this->paysLivraison = $paysLivraison;
        $this->nomcomplet = $nomcomplet;
        $this->rueLivraison = $rueLivraison;
    }
    public function __get($attr){
        return $this->attr;
    }
    public function __set($attr, $value){
        $this->$attr = $value;
    }

    public function __tostring(): string{
        return "Nom client:".$this->villeLivraison;
    }
    public static function load($id): Client{
        global $db;
        $newClient = new Client(idpersonne: -1,cpLivraison:"",villeLivraison:"",telephone:"",paysLivraison:"",nomcomplet:"",rueLivraison:"");
        $stm = $db->preapre(query: "select * from client where idpersonne=:id");
        $stm->bindValue(":id",$id);
        $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        
        foreach($row as $key=>$value){
            $newClient->$key = $value;
        }
        return $newClient;
    }
}