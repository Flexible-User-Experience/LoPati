<?php

namespace LoPati\BlogBundle\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="configuracio_translations",
 *   uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 * 	"locale", "object_id", "field"
 *   })}
 * )
 */
class ConfiguracioTranslation extends AbstractPersonalTranslation
{
	/**
	 * @ORM\ManyToOne(targetEntity="LoPati\BlogBundle\Entity\Configuracio", inversedBy="translations")
	 * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $object;
}
