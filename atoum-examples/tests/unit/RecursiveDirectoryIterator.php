<?php
namespace tests\unit;

use
    mageekguy\atoum,
    mageekguy\atoum\mock\stream,
    mageekguy\atoum\mock\streams\file,
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
                ->directory('directory')->create($directory)
            ->create()
            ->and(is_dir($directory))
            ->then
                ->directory($directory)
                    ->hasBeenChecked()
                    ->exists()
        ;
    }

    public function testCreateDirectory()
    {
        $this
            ->if($filesystem = null)
            ->mockFilesystem()->create($filesystem)            
            ->and($directory = $filesystem . DIRECTORY_SEPARATOR . 'directory')
            ->and(mkdir($directory))
            ->then
                ->directory(stream::get($directory))
                    ->hasBeenCreated()
                    ->exists()
        ;
    }

    public function testRemoveDirectory()
    {
        $this
            ->if($directory = null)
            ->mockFilesystem()
                ->directory('directory')->create($directory)                
            ->create()
            ->and(rmdir($directory))
            ->then
                ->directory($directory)
                    ->hasBeenDeleted()
                    ->doesNotExist()
                ->boolean(mkdir($directory))->isTrue()
        ;
    }

    public function testSubdirectory()
    {
        $this
            ->if($directory = $subdirectory = null)
            ->mockFilesystem()
                ->directory('directory')                    
                    ->directory('subdirectory')->create($subdirectory)
                ->create($directory)
            ->create()
            ->and(is_dir($directory))
            ->and(is_dir($subdirectory))
            ->then
                ->directory($directory)
                    ->hasBeenChecked()
                    ->exists()
                ->directory($subdirectory)
                    ->hasBeenChecked()
                    ->exists()
        ;
    }

    public function testRemoveSubdirectory()
    {
        $this
            ->if($directory = $subdirectory = null)
            ->mockFilesystem()
                ->directory('directory')                    
                    ->directory('subdirectory')->create($subdirectory)
                ->create($directory)
            ->create()
            ->and(is_dir($directory))
            ->and(rmdir($subdirectory))
            ->then
                ->directory($directory)
                    ->hasBeenChecked()
                    ->exists()
                ->directory($directory)
                    ->hasNotBeenDeleted()
                    ->exists()
                ->directory($subdirectory)
                    ->hasBeenDeleted()
                    ->doesNotExist()
        ;
    }

    public function testSingleFile()
    {
        $this
            ->if($file = null)
            ->mockFilesystem()
                ->file('file')->create($file)
            ->create()
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
            ->mockFilesystem()->create($filesystem)                     
            ->and(fopen($file = $filesystem . DIRECTORY_SEPARATOR . uniqid(), 'w+'))            
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
            ->if(unlink($file))
            ->then
                ->boolean(is_file($file))->isFalse()
            ->if(fopen($file, 'x+'))
            ->then
                ->adapter(stream::get($file))
                    ->call('stream_open')->once()
        ;
    }

    public function testRemoveFile()
    {
        $this
            ->if($file = null)
            ->mockFilesystem()
                ->file('file')->create($file)
            ->create()
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
                    ->directory('subdirectory')                        
                        ->file('file')->create($file)
                    ->create($subdirectory)
                ->create($directory)
            ->create()
            ->and(is_file($file))
            ->then
                ->adapter($file->getStream())
                    ->call('url_stat')->once()
        ;
    }

    public function testRemoveFileInSubdirectory()
    {
        $this
            ->if($directory = $subdirectory = $file = null)
            ->mockFilesystem()
                ->directory('directory')                    
                    ->directory('subdirectory')                        
                        ->file('file')->create($file)
                    ->create($subdirectory)
                ->create($directory)
            ->create()
            ->and(unlink($file))
            ->then
                ->adapter($file->getStream())
                    ->call('unlink')->once()
        ;
    }

    public function testRecursiveDirectoryIterator()
    {
        $this
            ->if($filesystem = $directory = $otherDirectory = $subdirectory = $file = $foobar = null)
            ->mockFilesystem(uniqid())                
                ->directory(uniqid())->create($directory)                
                ->directory(uniqid())                    
                    ->directory(uniqid())                        
                        ->directory(uniqid())->create()
                        ->file(uniqid())                            
                            ->content($content = uniqid())
                        ->create($file)
                    ->create($subdirectory)
                ->create($otherDirectory)
            ->create($filesystem)
            ->and($object = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($filesystem)))
            ->and(call_user_func(function() use($object) { foreach($object as $v) {} }))
            ->then
                ->directory($filesystem)
                    ->hasBeenOpened()
                    ->hasBeenRead()
                    ->hasBeenRewinded()
                    ->hasNotBeenClosed()
                ->directory($directory)
                    ->hasBeenOpened()
                    ->hasBeenRead()
                    ->hasBeenRewinded()
                    ->hasBeenClosed()
                ->directory($otherDirectory)
                    ->hasBeenOpened()
                    ->hasBeenRead()
                    ->hasBeenRewinded()
                    ->hasBeenClosed()
                ->directory($subdirectory)
                    ->hasBeenOpened()
                    ->hasBeenRead()
                    ->hasBeenRewinded()
                    ->hasBeenClosed()
                ->adapter($file->getStream())
                    ->call('url_stat')->withArguments((string) $file)
        ;
    }
}