<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class ProjectFixtures extends Fixture
{
    const PATH = __DIR__ . '/../../public/uploads/pictures_fixtures/';

    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < 12; $i++) {
            $project = new Project();
            $project
                ->setName('Project' . $i)
                ->setLink('http://' . $project->getName() . '.fr')
                ->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur 
                efficitur, ante nec aliquet viverra, ex quam euismod nisl, at porttitor lacus libero sit amet odio. 
                Praesent sit amet tortor quis lectus sagittis lobortis laoreet nec nisl. Ut ultricies nisl eget magna 
                fringilla gravida. Cras eget viverra enim. Aliquam rhoncus, nunc id lobortis ornare, nisl mi ultrices 
                quam, ut porttitor metus urna in tellus. In at vehicula lorem. Curabitur mollis libero quis risus
                 fermentum, quis tincidunt neque commodo. Vivamus ultrices magna vel mi faucibus, at convallis lorem 
                 tincidunt. Etiam tincidunt tempor augue, at maximus orci ornare vitae. Sed ut massa pellentesque, 
                 euismod felis ac, ultricies nibh.');

            for ($y = 0; $y < 2; $y++) {
                $picture = new Picture();

                $picture->setName(($y == 0) ? 'Accueil' : $y)
                    ->setFile(new File(($y == 0) ?
                            (!copy(self::PATH . '01.png', self::PATH . '01_copy.png') ?: self::PATH . '01_copy.png') :
                            (!copy(self::PATH . '02.png', self::PATH . '02_copy.png') ?: self::PATH . '02_copy.png')
                        )
                    );


                $project->addPicture($picture);
            }

            $manager->persist($project);
        }

        $manager->flush();

    }
}
