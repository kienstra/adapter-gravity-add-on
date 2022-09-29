<?php
/**
 * Email setting factory.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Not really needed, but keeps with the convention of using a factory to instantiate.
 */
class EmailSettingFactory {
	public static function create(): EmailSetting {
		return new EmailSetting();
	}
}
