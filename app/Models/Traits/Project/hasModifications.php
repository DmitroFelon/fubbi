<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 11/12/17
 * Time: 12:37
 */

namespace App\Models\Traits\Project;


/**
 * Used to modify projects services related
 * to selected Stripe plan.
 * Stored in project_meta
 *
 * @trait hasModifications
 *
 * @package App\Models\Traits\Project
 */
trait hasModifications
{
	/**
	 * @var array
	 */
	protected $modificator_fields = [
		'service',
		'value',
		'default',
	];

	/**
	 * @var string
	 */
	protected $modificator_slug = 'modificator_';

	/**
	 * @param $service
	 * @param $value
	 * @param bool $save
	 * @internal param null $default
	 */
	public function modify($service, $value, $save = true)
	{
		$this->setMeta($this->modificator_slug.$service, $value);
		if($save){
			$this->save();
		}
	}

	/**
	 * @param $service
	 */
	public function unmodify($service)
	{
		$this->unsetMeta($this->modificator_slug.$service);
		$this->save();
	}

	/**
	 * @param $service
	 * @return array|bool
	 */
	public function getModified($service)
	{
		return ($this->isModified($service))
			? $this->{$this->modificator_slug.$service}
			: false;
	}

	/**
	 * @param $service
	 * @return bool
	 */
	public function isModified($service)
	{
		return (
			isset($this->{$this->modificator_slug.$service}) and ! is_null($this->{$this->modificator_slug.$service})
		);
	}

	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function getModifications()
	{
		return $this->metas()->where('key', 'like', $this->modificator_slug.'%')->get();
	}
}