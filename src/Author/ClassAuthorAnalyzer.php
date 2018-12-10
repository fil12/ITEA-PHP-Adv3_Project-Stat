<?php

/*
 * This file is part of the "PHP Project Stat" project.
 *
 * (c) Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Author;

use phpDocumentor\Reflection\DocBlockFactory;
use Symfony\Component\Finder\Finder;

/**
 * Analyzer that provides information about classes authors.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
final class ClassAuthorAnalyzer
{
    private $rootDir;
    private $rootNamespace;

    public function __construct(string $rootDir, string $rootNamespace)
    {
        $this->rootDir = $rootDir;
        $this->rootNamespace = $rootNamespace;
    }

    public function analyze(string $email): int
    {
        $counter = 0;
        $factory = DocBlockFactory::createInstance();

        $finder = new Finder();
        $finder
            ->in($this->rootDir)
            ->files()
            ->name('/^[A-Z].+\.php$/')
        ;

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

            if (!$docComment = $reflection->getDocComment()) {
                continue;
            }

            $docBlock = $factory->create($docComment);
            /* @var \phpDocumentor\Reflection\DocBlock\Tags\Author[] $authors */
            $authors = $docBlock->getTagsByName('author');

            foreach ($authors as $author) {
                if ($author->getEmail() === $email) {
                    ++$counter;

                    break;
                }
            }
        }

        return $counter;
    }
}
