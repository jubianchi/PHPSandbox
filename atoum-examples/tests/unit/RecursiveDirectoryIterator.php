<?php
namespace tests\unit;

use
    mageekguy\atoum,
    mageekguy\atoum\mock\stream,
    DirectoryIterator as TestedClass
;

require_once __DIR__ . '/../../vendor/autoload.php';

class RecursiveDirectoryIterator extends atoum\test
{
    public function testSingleDirectory()
    {
        $this
            ->if($directory = null)
            ->mockFilesystem()
                ->directory('directory')
                    ->referencedBy($directory)
                ->close()
            ->close()
            ->then
                ->variable($directory)->isNotNull()
                ->boolean(is_dir($directory))->isTrue()
                ->boolean(is_file($directory))->isFalse()
                ->adapter($directory->getStream())
                    ->call('url_stat')->exactly(2)
        ;
    }

    public function testCreateDirectory()
    {
        $this
            ->if($filesystem = null)
            ->mockFilesystem()
                ->referencedBy($filesystem)
            ->close()
            ->and($directory = $filesystem . DIRECTORY_SEPARATOR . 'directory')
            ->and(mkdir($directory))
            ->then
                ->boolean(is_dir($directory))->isTrue()
                ->boolean(is_file($directory))->isFalse()
                ->adapter(stream::get($directory))
                    ->call('url_stat')->exactly(2)
                    ->call('mkdir')->once()
        ;
    }

    public function testRemoveDirectory()
    {
        $this
            ->if($directory = null)
            ->mockFilesystem()
                ->directory('directory')
                    ->referencedBy($directory)
                ->close()
            ->close()
            ->and(rmdir($directory))
            ->then
                ->variable($directory)->isNotNull()
                ->boolean(is_dir($directory))->isFalse()
                ->boolean(is_file($directory))->isFalse()
                ->adapter($directory->getStream())
                    ->call('rmdir')->once()
                ->boolean(mkdir($directory))->isTrue()
        ;
    }

    public function testSubdirectory()
    {
        $this
            ->if($directory = $subdirectory = null)
            ->mockFilesystem()
                ->directory('directory')
                    ->referencedBy($directory)
                    ->directory('subdirectory')
                        ->referencedBy($subdirectory)
                    ->close()
                ->close()
            ->close()
            ->then
                ->variable($directory)->isNotNull()
                ->boolean(is_dir($directory))->isTrue()
                ->boolean(is_file($directory))->isFalse()
                ->variable($subdirectory)->isNotNull()
                ->boolean(is_dir($subdirectory))->isTrue()
                ->variable(clearstatcache(true, $directory))
                ->boolean(is_file($subdirectory))->isFalse()
                ->adapter($directory->getStream())
                    ->call('url_stat')->exactly(2)
                ->adapter($subdirectory->getStream())
                    ->call('url_stat')->exactly(2)
        ;
    }

    public function testRemoveSubdirectory()
    {
        $this
            ->if($directory = $subdirectory = null)
            ->mockFilesystem()
                ->directory('directory')
                    ->referencedBy($directory)
                    ->directory('subdirectory')
                        ->referencedBy($subdirectory)
                    ->close()
                ->close()
            ->close()
            ->and(rmdir($subdirectory))
            ->then
                ->variable($directory)->isNotNull()
                ->boolean(is_dir($directory))->isTrue()
                ->boolean(is_file($directory))->isFalse()
                ->variable($subdirectory)->isNotNull()
                ->boolean(is_dir($subdirectory))->isFalse()
                ->boolean(is_file($subdirectory))->isFalse()
                ->adapter($directory->getStream())
                    ->call('url_stat')->exactly(2)
                    ->call('rmdir')->never()
                ->adapter($subdirectory->getStream())
                    ->call('rmdir')->once()
        ;
    }

    public function testSingleFile()
    {
        $this
            ->if($file = null)
            ->mockFilesystem()
                ->file('file')
                    ->referencedBy($file)
                ->close()
            ->close()
            ->then
                ->variable($file)->isNotNull()
                ->boolean(is_dir($file))->isFalse()
                ->boolean(is_file($file))->isTrue()
                ->adapter($file->getStream())
                    ->call('url_stat')->exactly(2)
        ;
    }

    public function testCreateFile()
    {
        $this
            ->if($filesystem = null)
            ->mockFilesystem()
                ->referencedBy($filesystem)
            ->close()
            ->and($file = $filesystem . DIRECTORY_SEPARATOR . uniqid())
            ->and(fopen($file, 'w+'))
            ->then
                ->adapter(stream::get($file))
                    ->call('stream_open')->once()
            ->assert()
            ->if(fopen($file, 'a+'))
            ->then
                ->adapter(stream::get($file))
                    ->call('stream_open')->once()
            ->assert()
            ->if(fopen($file, 'c+'))
            ->then
                ->adapter(stream::get($file))
                    ->call('stream_open')->once()
                ->exception(function() use ($file) {
                    fopen($file, 'x+');
                })
                    ->isInstanceOf('\\mageekguy\\atoum\\exceptions\\logic')
                    ->hasMessage('Stream \'' . $file . '\' already exists')
        ;
    }

    public function testRemoveFile()
    {
        $this
            ->if($file = null)
            ->mockFilesystem()
                ->file('file')
                    ->referencedBy($file)
                ->close()
            ->close()
            ->and(unlink($file))
            ->then
                ->variable($file)->isNotNull()
                ->boolean(is_dir($file))->isFalse()
                ->boolean(is_file($file))->isFalse()
                ->adapter($file->getStream())
                    ->call('unlink')->once()
        ;
    }

    public function testSingleFileInSubdirectory()
    {
        $this
            ->if($directory = $subdirectory = $file = null)
            ->mockFilesystem()
                ->directory('directory')
                    ->referencedBy($directory)
                    ->directory('subdirectory')
                        ->referencedBy($subdirectory)
                        ->file('file')
                            ->referencedBy($file)
                        ->close()
                    ->close()
                ->close()
            ->close()
            ->then
                ->variable($directory)->isNotNull()
                ->boolean(is_dir($directory))->isTrue()
                ->boolean(is_file($directory))->isFalse()
                ->variable($subdirectory)->isNotNull()
                ->boolean(is_dir($subdirectory))->isTrue()
                ->variable(clearstatcache(true, $directory))
                ->boolean(is_file($subdirectory))->isFalse()
                ->variable($file)->isNotNull()
                ->boolean(is_dir($file))->isFalse()
                ->variable(clearstatcache(true, $file))
                ->boolean(is_file($file))->isTrue()
                ->adapter($directory->getStream())
                    ->call('url_stat')->exactly(2)
                ->adapter($subdirectory->getStream())
                    ->call('url_stat')->exactly(2)
                ->adapter($file->getStream())
                    ->call('url_stat')->exactly(2)
        ;
    }

    public function testRemoveFileInSubdirectory()
    {
        $this
            ->if($directory = $subdirectory = $file = null)
            ->mockFilesystem()
                ->directory('directory')
                    ->referencedBy($directory)
                    ->directory('subdirectory')
                        ->referencedBy($subdirectory)
                        ->file('file')
                            ->referencedBy($file)
                        ->close()
                    ->close()
                ->close()
            ->close()
            ->and(unlink($file))
            ->then
                ->variable($directory)->isNotNull()
                ->boolean(is_dir($directory))->isTrue()
                ->boolean(is_file($directory))->isFalse()
                ->variable($subdirectory)->isNotNull()
                ->boolean(is_dir($subdirectory))->isTrue()
                ->variable(clearstatcache(true, $directory))
                ->boolean(is_file($subdirectory))->isFalse()
                ->variable($file)->isNotNull()
                ->boolean(is_dir($file))->isFalse()
                ->variable(clearstatcache(true, $file))
                ->boolean(is_file($file))->isFalse()
                ->adapter($directory->getStream())
                    ->call('url_stat')->exactly(2)
                ->adapter($subdirectory->getStream())
                    ->call('url_stat')->exactly(2)
                ->adapter($file->getStream())
                    ->call('unlink')->once()
        ;
    }

    public function testRecursiveDirectoryIterator()
    {
        $this
            ->if($filesystem = $directory = $otherDirectory = $subdirectory = $file = $foobar = null)
            ->mockFilesystem(uniqid())
                ->referencedBy($filesystem)
                ->directory(uniqid())
                    ->referencedBy($directory)
                ->close()
                ->directory(uniqid())
                    ->referencedBy($otherDirectory)
                    ->directory(uniqid())
                        ->referencedBy($subdirectory)
                        ->file(uniqid())
                            ->referencedBy($file)
                            ->content($content = uniqid())
                        ->close()
                    ->close()
                ->close()
            ->close()
            ->and($object = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($filesystem)))
            ->and(call_user_func(function() use($object) { foreach($object as $v) {} }))
            ->then
                ->adapter($filesystem->getStream())
                    ->call('dir_opendir')->once()
                    ->call('dir_readdir')->exactly(3)
                    ->call('dir_rewinddir')->once()
                ->adapter($directory->getStream())
                    ->call('dir_opendir')->once()
                    ->call('dir_readdir')->once()
                    ->call('dir_rewinddir')->once()
                    ->call('dir_closedir')->once()
                ->adapter($otherDirectory->getStream())
                    ->call('dir_opendir')->once()
                    ->call('dir_readdir')->exactly(2)
                    ->call('dir_rewinddir')->once()
                    ->call('dir_closedir')->once()
                ->adapter($subdirectory->getStream())
                    ->call('dir_opendir')->once()
                    ->call('dir_readdir')->exactly(2)
                    ->call('dir_rewinddir')->once()
                    ->call('dir_closedir')->once()
                ->adapter($file->getStream())
                    ->call('url_stat')->withArguments((string) $file)
        ;
    }
}