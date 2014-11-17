<?php

namespace LoPati\ArtistaBundle\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="artista_translations",
 *   uniqueConstraints={@ORM\UniqueConstraint(name="lookup_artist_unique_idx", columns={
 * 	"locale", "object_id", "field"
 *   })}
 * )
 */
class ArtistaTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="LoPati\ArtistaBundle\Entity\Artista", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
