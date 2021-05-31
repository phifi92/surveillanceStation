<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }

    ajax::init();

    // Execution PostSave
    if (init('action') == 'postSave') {
        //Called after a plugin configuration save
        // Let's first check new configuration values
        try {
            // Default Value
            if (config::byKey('snapLocation', 'surveillanceStation') == 'jeedom') {
                if (empty(config::byKey('snapPreEventDelay', 'surveillanceStation'))) {
                    config::save('snapPreEventDelay', init('default_snapPreEventDelay'), 'surveillanceStation');
                }
                if (empty(config::byKey('snapPostEventDelay', 'surveillanceStation'))) {
                    config::save('snapPostEventDelay', init('default_snapPostEventDelay'), 'surveillanceStation');
                }
                if (empty(config::byKey('snapRetention', 'surveillanceStation'))) {
                        config::save('snapRetention', init('default_snapRetention'), 'surveillanceStation');
                }
            }
            surveillanceStation::checkConfig();
        } catch (Exception $e) {
            //Invalid configuration.
            //Let's first set back the old values
            config::save('ip', init('ip'), 'surveillanceStation');
            config::save('port', init('port'), 'surveillanceStation');
            config::save('https', init('https'), 'surveillanceStation');
            config::save('user', init('user'), 'surveillanceStation');
            config::save('password', init('password'), 'surveillanceStation');
            config::save('oauth', init('oauth'), 'surveillanceStation');
            config::save('snapLocation', init('snapLocation'), 'surveillanceStation');
            config::save('snapPreEventDelay', init('snapPreEventDelay'), 'surveillanceStation');
            config::save('snapPostEventDelay', init('snapPostEventDelay'), 'surveillanceStation');
            config::save('snapRetention', init('snapRetention'), 'surveillanceStation');
            //Let's then the error details
            ajax::error(displayExeption($e), $e->getCode());
        }

        ajax::success();
    }

    if (init('action') == 'discover') {
      ajax::success(surveillanceStation::discover());
    }

    if (init('action') == 'getsurveillanceStation') {
			foreach (jeeObject::all() as $object) {
				foreach ($object->getEqLogic(true, false, 'surveillanceStation') as $surveillanceStation) {
					$return['eqLogics'][] = $surveillanceStation->toHtml(init('version'));
				}
			}
		ajax::success($return);
    }

    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
