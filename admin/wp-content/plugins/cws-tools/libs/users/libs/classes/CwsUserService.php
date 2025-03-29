<?php

class CwsUserService extends CwsBaseService
{
	/**
	 * Returns user's full name (First name, Last name)
	 * 
	 * @param [Integer] $user_id
	 * @return String 
	 */
	public function getUserFullName($user_id)
	{
		$first_name = get_user_meta($user_id, 'first_name', true);
		$last_name 	= get_user_meta($user_id, 'last_name', true);

		$name_array = [];

		if ($first_name) { $name_array[] = $first_name; }
		if ($last_name) { $name_array[] = $last_name; }

		return implode(' ', $name_array);
	}
}