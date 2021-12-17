<?php

/**
 * 
 * @return array
 */
function getColors() {
    return TP2AutosLibrary::colors();
}

/**
 * 
 * @return array
 */
function getModels() {
    return TP2AutosLibrary::models();
}

/**
 * 
 * @return array
 */
function getStocks() {
    return TP2AutosLibrary::getInstance()->stocks();
}

/**
 * 
 * @return array
 */
function getSales() {
    return TP2AutosLibrary::getInstance()->sales();
}

/**
 * Enregistre une nouvelle voiture dans le stock
 * @param string $color la couleur de la voiture
 * @param string $model le modèle de la voiture
 * @param int $price le prix d'achat
 * @return bool
 */
function addStock(string $color, string $model, int $price): bool {
    return TP2AutosLibrary::getInstance()->add($color, $model, $price);
}

/**
 * Enregistre la vente d'une voiture en stock.
 * @param int $id l'identifiant de la voiture en stock
 * @param int $price le prix de vente
 * @return bool
 */
function sellCar(int $id, int $price): bool {
    return TP2AutosLibrary::getInstance()->sell($id, $price);
}

class TP2AutosLibrary {
    const DBN = 'tp2_autos';
    
    /**
     *
     * @var TP2AutosLibrary
     */
    protected static $singleton = null;
    
    /**
     * 
     * @return TP2AutosLibrary
     */
    public static function getInstance() {
        if(null === static::$singleton) {
            static::$singleton = new TP2AutosLibrary();
            static::$singleton->assertInstall();
        }
        return static::$singleton;
    }
    
    protected $sql = null;
    
    public function __construct() {
        $this->sql = new PDO('mysql:host=localhost', SQLUSR, SQLPWD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }
    
    public static function colors(): array {
        return [
            'noir',
            'blanc',
            'gris',
            'argent',
            'jaune',
            'rouge',
            'bleu',
            'vert',
        ];
    }
    
    public static function models(): array {
        return [
            'ford',
            'peugeot',
            'renault',
            'tesla',
        ];
    }
    
    public function assertInstall(): bool {
        $returns = false;
        $r = $this->sql->query("show databases like 'tp2_autos'");
        if(empty($r) || (0 === $r->rowCount())) {
            $this->install();
            $returns = true;
        } else {
            $this->sql->query('use `'.static::DBN.'`');
        }
        return $returns;
    }
    
    protected function install() {
        $this->sql->query('create database `'.static::DBN.'`');
        $this->sql->query('use `'.static::DBN.'`');
        $colors = "'".implode("', '", static::colors())."'";
        $models = "'".implode("', '", static::models())."'";
        $q = <<<EOT
create table if not exists `cars` (
    `id` int not null primary key auto_increment,
    `model` enum({$models}) not null,
    `color` enum({$colors}) not null,
    `price` int not null default 0,
    `soldPrice` int default null,
    `entry` datetime not null,
    `soldDate` datetime default null
) engine=InnoDB default character set 'utf8'
EOT;
        $this->sql->query($q);
    }
    
    public function stocks(): array {
        $stmt = $this->sql->query('select `id`, `model`, `color`, `price`, `entry` from `cars` where `soldDate` is null');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function sales(): array {
        $stmt = $this->sql->query('select `model`, `color`, `price`, `soldPrice`, `soldDate` from `cars` where `soldDate` is not null');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function add(string $color, string $model, int $price): bool {
        if(!in_array($color, static::colors())) {
            throw new UnexpectedValueException('Value "'.$color.'" is not allowed for color, only one of "'.implode('", "', static::colors()).'" expected');
        }
        if(!in_array($model, static::models())) {
            throw new UnexpectedValueException('Value "'.$model.'" is not allowed for model, only one of "'.implode('", "', static::models()).'" expected');
        }
        if(0 > $price) {
            throw new OutOfRangeException('Value "'.$price.'" is not allowed for price, only greater or equal to zero value expected');
        }
        $stmt = $this->sql->prepare('insert ignore into `cars` (`model`, `color`, `price`, `entry`) values(:m, :c, :p, now())');
        return $stmt->execute(['m' => $model, 'c' => $color, 'p' => $price]);
    }
    
    public function sell(int $id, int $price): bool {
        if(0 >= $price) {
            throw new OutOfRangeException('Value "'.$price.'" is not allowed for price, only greater to zero value expected');
        }
        $stmt = $this->sql->prepare('update `cars` set `soldDate`=now(), `soldPrice`=:p where `id`=:k and `soldDate` is null');
        return $stmt->execute(['k' => $id, 'p' => $price]);
    }
}

TP2AutosLibrary::getInstance();
