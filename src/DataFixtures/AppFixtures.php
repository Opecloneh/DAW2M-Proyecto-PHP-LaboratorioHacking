<?php

namespace App\DataFixtures;

use App\Entity\Module;
use App\Entity\Laboratory;
use App\Entity\Achievement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Módulos
        $ModulesData = [
            ['SQL Injection', 'Vulnerabilidades relacionadas con inyecciones SQL.'],
            ['XSS', 'Cross-Site Scripting y ejecución de JavaScript en el navegador.'],
            ['IDOR', 'Insecure Direct Object Reference y fallos de autorización.'],
            ['File Upload', 'Vulnerabilidades en sistemas de subida de archivos.'],
        ];

        $Modules = [];
        foreach ($ModulesData as [$name, $desc]) {
            $Module = new Module();
            $Module->setName($name);
            $Module->setDescription($desc);
            $manager->persist($Module);
            $Modules[] = $Module;
        }

        // Laboratorys
        $LaboratorysData = [
            ['SQLi Login – Login Bypass', 'Realiza un login bypass explotando una vulnerabilidad SQL Injection en el formulario de autenticación con un Boolean Based.', 'Fácil', 0, "' OR 1=1 -- "],
            ['XSS Reflejado', 'Explotar un XSS reflejado en un parámetro vulnerable de búsqueda, con una alerta donde pongas "1".', 'Media', 1, '<script>alert("1")</script>'],
            ['IDOR Básico', 'Accede al usuario con id 4 teniendo en cuenta que tu URL es /user/2.', 'Fácil', 2, '/user/4'],
            ['File Upload Inseguro', 'Subir una webshell shell.php teniendo en cuenta que el servidor solo acepta .jpg.', 'Media', 3, 'shell.php.jpg'],
        ];

        foreach ($LaboratorysData as [$title, $desc, $difficulty, $modIndex, $solution]) {
            $lab = new Laboratory();
            $lab->setTitle($title);
            $lab->setDescription($desc);
            $lab->setDifficulty($difficulty);
            $lab->setSolution($solution);
            $lab->setModule($Modules[$modIndex]); // Relación
            $manager->persist($lab);
        }

        $manager->flush();
    }
}
