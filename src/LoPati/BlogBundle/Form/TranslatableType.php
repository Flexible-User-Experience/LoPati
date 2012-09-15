<?php
class TranslatableType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
		// creates an input field for each locale with the name
		// fieldName_locale
		foreach ($options['locales'] as $locale) {

			//here modification BEGIN
			$name = $locale.'Translation.'.$builder->getName() ;
			//here modification END

			$builder->add($name, $options['type'], array());
		}
	}

	public function getDefaultOptions(array $options)
	{
		return array_merge($options, array('type' => null));
	}

	public function getName()
	{
		return 'sonata_type_translatable';
	}
}