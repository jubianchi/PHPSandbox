# Tests unitaires et Adapters en PHP avec atoum

Nous avons récemment eu quelques discussions sur les ```Adapters``` sur le salon IRC de atoum (pour rappel, celui-ci se trouve sur les serveurs Freenode, canal ##atoum). Plusieurs questions ont été traitées : nous avons parlé de l'utilité de ces ```Adapters```, des possibilités qu'ils offrent dans le cadre de tests unitaires mais également des inconvénients et des bonnes pratiques à mettre en place pour bien les utiliser. Je vais donc tenter ici d'eclaircir ces quelques points à travers des exemples relativement simples. J'utiliserais _atoum_ pour les tests qui seront écrits pour une classe gérant une connexion à un serveur FTP.

## Le pattern Adapter

Avant de commencer, nous allons faire un petit rappel sur le design pattern ```Adapter```. Voici un extrait de la définition donnée par Wikipedia :

> The adapter translates calls to its interface into calls to the original interface, and the amount of code necessary to do this is typically small.
>
> — [Wikipedia - Adapter Pattern](http://en.wikipedia.org/wiki/Adapter_pattern)

Cette définition nous dit que l'```Adapter``` traduit des appels d'une interface vers une autre avec un minimum de code. En d'autres termes, elle n'est qu'un proxy.

## L'intérêt des Adapters pour les tests unitaires

Lorsqu'on souhaite utiliser les tests unitaires pour valider notre code de la manière la plus approfondie possible, il est souvent nécessaires d'utiliser certaines bonnes pratiques qui permettent de mettre en place cette stratégie. Vous avez donc certainement entendu parler de l'injection de dépendances par exemple qui règle très bien certains problèmes : ma classe testée à besoin d'une autre classe pour fonctionner et celle-ci lui sera injectée au moment voulu dans les tests, idéalement sous forme de mock. Mais qu'en est-il lorsque la classe testée est directement dépendante de l'environement.

Mais qu'est ce que "dépendante de l'environement" signifie ? A un moment, vous vous retrouverez certainement avec des méthodes qui utilisent des fonctions natives de PHP pour tester l'existence d'un fichier par exemple ou, pour rester dans le cadre de l'exemple que je vais introduire par la suite, qui se connecte à des serveurs FTP toujours via les fonctions natives du langage. A ce moment là, vous aurez certainement des difficultés pour tester tous les cas possibles dans votre code : mon fichier existe ou n'existe pas, le serveur FTP est disponible, le login entré est invalide, ... Les ```Adapters``` sont là pour nous permettre de tester ces cas très simplement et de manière très intuitive (en tout cas avec _atoum_)

## Sans les Adapters

Comme je vous le disais, nous allons travailler sur un exemple présentant une classe qui permet de se connecter à un serveur FTP via les fonctions natives de PHP. Cet exemple est assez basique mais il a l'avantage de montrer rapidement les problèmes que l'on peut rencontrer :

~~~php
<?php
namespace tests\unit {
    use 
        mageekguy\atoum,
        Ftp as TestedClass
    ;

    class Ftp extends atoum\test {
        public function test__construct() {
            $this
                ->object(new TestedClass())->isInstanceOf('\\Ftp')
            ;   
        }
    }
}

namespace {
    class Ftp
    {
        public function __construct()
        {
            if (false === extension_loaded('ftp')) {
                throw new \RuntimeException('FTP extension is not loaded');
            }
        }
 
        //...
    }
}
~~~

~~~shell
$ bin/atoum -d tests/listing/1.php

> tests\unit\Ftp...
[S___________________________________________________________][1/1]
=> Test duration: 0.00 second.
=> Memory usage: 0.50 Mb.
> Total test duration: 0.00 second.
> Total test memory usage: 0.50 Mb.
> Code coverage value: 66.67%
=> Class Ftp: 66.67%
==> Ftp::__construct(): 66.67%
~~~

Nous avons donc notre test unitaire qui va vérifier que l'instanciation de notre classe ```Ftp``` se passe bien. Pour que ce test passe, il faudra obligatoirement que la machine qui l'exécute ait l'extension PHP FTP installée. Si cette condition n'est pas respectée, une exception sera levée et notre test passera au rouge.

Si nous souhaitons tester cette exception, il faudra obligatoirement que la machine exécutant les tests n'ait pas l'extension requise.

En d'autres termes, il va être très difficile de tester les deux cas dans un seul test unitaire exécuté sur la même machine à moins de passer par des "hacks".

Rappelez-vous bien une chose : nous sommes dans le cadre de tests unitaires et il serait très dommage de les coupler à l'environnement sur lequel ils sont exécutés, c'est donc là que les ```Adapters``` vont nous aider.

## Comment utiliser les Adapters

Les classes qui utiliseront l'```Adapter``` auront donc une dépendance supplémentaire vers ce proxy. Ne vous inquiétez pas, dans l'idéal, votre code sera fait de telle manière que l'injection de cette dépendance soit optionnelle. Voyons tout de suite un extrait de code qui illustre mes propos :

~~~php
<?php
use 
    mageekguy\atoum
;

class Ftp
{
    private $adapter;

    public function __construct(atoum\adapter $adapter = null)
    {
        $this->setAdapter($adapter);

        if (false === $this->getAdapter()->extension_loaded('ftp')) {
            throw new \RuntimeException('FTP extension is not loaded');
        }
    }
    
    public function setAdapter(atoum\adapter $adapter = null)
    {
        $this->adapter = $adapter;

        return $this;
    }
    
    public function getAdapter()
    {
        if (null === $this->adapter) {
            $this->adapter = new atoum\adapter();
        }

        return $this->adapter;
    }

    //...
}
~~~

Comme vous pouvez le constater à travers cet extrait de code, l'injection de l'```Adapter``` est optionnelle et dans le cas où celui-ci n'est pas fourni, un ```Adapter``` par défaut sera créé et utilisé au sein de la classe ```Ftp```. Nous autorisons également l'injection de cette dépendance par le constructeur et par un mutateur (setter) : cela nous permettra d'injecter un ```Adapter``` adéquat dans les tests unitaires.

Vous avez certainement noté que le constructeur de notre classe ```Ftp``` a été modifié : l'appel à ```extension_loaded``` passe désormais par notre ```Adapter``` et cela va nous permettre d'étoffer notre test unitaire et d'y ajouter quelques cas supplémentaires :

~~~php
<?php
class Ftp extends atoum\test {
    public function test__construct() {
        $this
            ->if($adapter = new atoum\test\adapter())
            ->and($adapter->extension_loaded = true)
            ->then
                ->object(new TestedClass($adapter))->isInstanceOf('\\Ftp')  

            ->if($adapter->extension_loaded = false)
            ->then
                ->exception(
                    function() use($adapter) {
                        new TestedClass($adapter); 
                    }
                )
                    ->isInstanceOf('\\RuntimeException')
                    ->hasMessage('FTP extension is not loaded') 
        ;
    }
}
~~~

~~~shell
$ bin/atoum -d tests/listing/2-3.php

> tests\unit\Ftp...
[S___________________________________________________________][1/1]
=> Test duration: 0.01 second.
=> Memory usage: 0.50 Mb.
> Total test duration: 0.01 second.
> Total test memory usage: 0.50 Mb.
> Code coverage value: 80.00%
=> Class Ftp: 80.00%
==> Ftp::getAdapter(): 50.00%
~~~

On se rend immédiatement compte des avantages que l'```Adapter``` nous procure : nous sommes maintenant capable de tester notre classe sans nous soucier de la configuration de la machine exécutant les tests. Et en bonus, nous avons la possibilité de simuler la présence ou l'absence de l'extension ce qui nous permet d'obtenir une couverture plus importante (nous sommes passé de 67% à 80%) !

Notre code y gagne en testabilité et donc en fiabilité si les tests adéquats sont mis en place. La contrepartie, c'est que nous sommes désormais dépendant de l'```Adapter``` et que cela peut nous jouer des tours dans certains cas (les performances peuvent par exemples être impactées).

## Attentions aux pièges !

### Ne pas être dépendant du framework de test dans le code de production

L'utilisation des ```Adapters``` apporte beaucoup de chose mais il faut les utiliser avec précaution. Si vous remontez au paragraphe précédent, vous verrez que le code de la classe ```Ftp``` est maintenant dépendant de l'```Adapter``` par défaut d'atoum : dans l'idéal, il faut éviter cela ! Votre code de production ne doit en aucun cas avoir de dépendance forte sur votre framework de test ! Mais comment contourner cela ?

Afin de décoreller votre projet du framework de test, il vous faudra écrire quelques interfaces qui vous permettront d'abstraire cette dépendance et éventuellement de migrer vers un autre framework de test sans trop de douleur.

Je vais vous présenter ici la méthode que j'utilise afin d'arriver à un tel résultat, à savoir, un ```Adapter``` indépendant du framework de test :

~~~php
<?php
namespace {
    interface AdapterInterface
    {
        public function invoke($name, array $args = array());
    }

    class Adapter implements AdapterInterface
    {
        public function invoke($name, array $args = array())
        {
            if (is_callable($name)) {
                return call_user_func_array($name, $args);
            }

            throw new \RuntimeException(sprintf('%s is not callable', var_export($name)));
        }

        public function __call($name, $args)
        {
            return $this->invoke($name, $args);
        }
    }
}

namespace Test {
    use
        mageekguy\atoum\test\adapter as AtoumAdapter
    ;

    class Adapter extends AtoumAdapter implements \AdapterInterface
    {
    }
}
~~~

Nous définissons donc, dans notre projet, une interface standard qui décrit nos ```Adapters``` ainsi, nos classes seront dépendantes de cette abstraction : 

~~~php
<?php
class Ftp
{
    private $adapter;

    public function __construct(AdapterInterface $adapter = null)
    {
        $this->setAdapter($adapter);

        if (false === $this->getAdapter()->extension_loaded('ftp')) {
            throw new \RuntimeException('FTP extension is not loaded');
        }
    }
    
    public function setAdapter(AdapterInterface $adapter = null)
    {
        $this->adapter = $adapter;

        return $this;
    }
    
    public function getAdapter()
    {
        if (null === $this->adapter) {
            $this->adapter = new Adapter();
        }

        return $this->adapter;
    }

    //...
}
~~~

~~~shell
$ bin/atoum -d tests/listing/4-5.php

> tests\unit\Ftp...
[S___________________________________________________________][1/1]
=> Test duration: 0.01 second.
=> Memory usage: 0.50 Mb.
> Total test duration: 0.01 second.
> Total test memory usage: 0.50 Mb.
> Code coverage value: 80.00%
=> Class Ftp: 80.00%
==> Ftp::getAdapter(): 50.00%
~~~

Comme vous pouvez le voir, la dépendance vers _atoum_ a disparue de notre code de production ! Nous sommes maintenant dépendant de notre ```Adapterinterface``` et de l'implémentation par défaut que nous avons ajoutée.