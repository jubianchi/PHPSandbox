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
            ->mockFilesystem()
                ->referencedBy($filesystem)
            ->close()
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
                ->directory('directory')
                    ->referencedBy($directory)
                ->close()
            ->close()
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
                    ->referencedBy($directory)
                    ->directory('subdirectory')
                        ->referencedBy($subdirectory)
                    ->close()
                ->close()
            ->close()
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
                    ->referencedBy($directory)
                    ->directory('subdirectory')
                        ->referencedBy($subdirectory)
                    ->close()
                ->close()
            ->close()
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
                        ->directory(uniqid())->close()
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