<?php

/*
 * This file is part of the "PHP Project Stat" project.
 *
 * (c) Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Analyzers;

use App\Traites\CounterModifiersTraite;
use phpDocumentor\Reflection\DocBlockFactory;
use Symfony\Component\Finder\Finder;


final class ClassesAnalyzer
{

    use CounterModifiersTraite;

    private $rootDir;
    private $rootNamespace;

    public function __construct(string $rootDir, string $rootNamespace)
    {
        $this->rootDir = $rootDir;
        $this->rootNamespace = $rootNamespace;
    }

    public function analyze(string $className): string
    {
        $finder = new Finder();
        $finder
            ->in($this->rootDir)
            ->files()
            ->name($className . '.php');

        foreach ($finder as $file) {
            $path = $file->getRelativePathname();
            $fullClassName = $this->rootNamespace
                . '\\'
                . \str_replace('/', '\\', \rtrim($path, '.php'));


            try {
                $reflection = new \ReflectionClass($fullClassName);
            } catch (\ReflectionException $e) {
                continue;
            }

            $propertiesModifiers = $this->getCountModifiers($reflection->getProperties());
            $staticPropertiesModifiers = $this->getCountModifiers($reflection->getStaticProperties());
            $methodsModifiers = $this->getCountModifiers($reflection->getMethods());
            $classModifier = \Reflection::getModifierNames($reflection->getModifiers())[0];

            $report = "Class: " . $className . " ( " . $classModifier . " class)
        Properties:
            public: " . $propertiesModifiers['public'] . " (" . $staticPropertiesModifiers['public'] . " static)
            protected:  " . $propertiesModifiers['protected'] . " (" . $staticPropertiesModifiers['protected'] . " static)
            private:  " . $propertiesModifiers['private'] . " (" . $staticPropertiesModifiers['private'] . " static)
        Methods:
            public: " . $methodsModifiers['public'] . " 
            protected: " . $methodsModifiers['protected'] . "
            private: " . $methodsModifiers['private'];

        }
        return $report;
    }
}
