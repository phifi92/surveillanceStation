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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
	include_file('desktop', '404', 'php');
	die();
}
?>
<form class="form-horizontal">
	<fieldset>
		<legend>
			<i class="fas fa-key"></i> {{Accès à votre Synology}}
		</legend>
		<div class="form-group">
			<label class="col-sm-4 control-label">{{Adresse DNS de votre Synolgy}}</label>
			<div class="col-sm-3">
				<input type="text" class="configKey form-control" data-l1key="ip" placeholder="NomDomaineDeMonSynology.tld"/>
			</div>
		</div>
		</br>
		<div class="form-group">
			<label class="col-sm-4 control-label"></label>
			<div class="col-sm-7">
				<div class="alert alert-info">
				<p>Pour que le live soit accessible de l'extérieur, il est important de renseigner l'adresse DNS de votre Synology DSM (MonSynology.tld) et non celle de Surveillance Station.
				Cette même adresse doit aussi fonctionner de votre réseau local.</br>
				A contrario, avec un IP LAN, le Live sera accessible seulement de votre réseau local.</p>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">{{N° de Port}}</label>
			<div class="col-sm-3">
				<input type="text" class="configKey form-control" data-l1key="port" placeholder="443"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">{{Connexion sécurisée}}</label>
			<div class="col-sm-1">
				<input type="checkbox" class="configKey" data-l1key="https" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">{{Identifiant Surveillance Station}}</label>
			<div class="col-sm-3">
				<input type="text" class="configKey form-control" data-l1key="user" placeholder="Nom d'utilisateur"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">{{Mot de passe Surveillance Station}}</label>
			<div class="col-sm-3">
				<input type="password" class="configKey form-control" data-l1key="password" placeholder="Mot de passe de l'utilisateur"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">{{Vérification en 2 étapes (optionnelle)}}</label>
			<div class="col-sm-3">
				<input type="text" class="configKey form-control" data-l1key="oauth" placeholder=""/>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>
			<i class="fas fa-video"></i> {{Snapshot Image et Snapshot Vidéo}}
		</legend>
		<div class="form-group">
			<label class="col-sm-4 control-label">{{Mode d'accès aux Snapshots}}
				<sup><i class="fa fa-question-circle tooltips" title="Les snapshots peuvent être accessible au travers de Synology ou bien localement à Jeedom (utile sur votre Synology n'est pas accessible d'internet)"></i></sup>
			</label>
			<div class="col-sm-3">
				<select class="configKey form-control" data-l1key="snapLocation">
                    <option value="synology">Synology</option>
                    <option value="jeedom">Jeedom</option>
                </select>
			</div>
		</div>
		<div class="form-group snapOnJeedom">
			<label class="col-sm-4 control-label">{{Nombre de Retention }}
				<sup><i class="fa fa-question-circle tooltips" title="Nombre de snapshot conservée autorisée sur Jeedom. Au dela, les plus anciens seront supprimés"></i></sup>
			</label>
			<div class="col-sm-3">
				<input type="text" class="configKey form-control" data-l1key="snapRetention" type="number" min="0" placeholder="10"/>
			</div>
		</div>
	</fieldset>
</form>

<script>

    // When page ready
    jQuery(document).ready(function($){
        // If snapLocation is equal to jeedom, we show snapRetention Field, else we hide it
        if($('.configKey[data-l1key=snapLocation]').value() == 'jeedom') {
            $( '.snapOnJeedom' ).show();
        } else {
            $( '.snapOnJeedom' ).hide();
        }
    });

    // When change on snapLocation, we show or hide snapRetention fields
    $('.configKey[data-l1key=snapLocation]').on('change', function () {
        if ($('.configKey[data-l1key=snapLocation]').value() == 'jeedom') {
            $( '.snapOnJeedom' ).show();
        } else {
            $( '.snapOnJeedom' ).hide();
        }
    });

	// Ajax call to verify plugin Configuration
    function surveillanceStation_postSaveConfiguration(){
        $.ajax({
            type: "POST",
            url: "plugins/surveillanceStation/core/ajax/surveillanceStation.ajax.php",
            data: {
                action: "postSave",
                //Let's send previous values as parameters, to be able to set them back in case of bad values
                ip: "<?php echo config::byKey('ip', 'surveillanceStation'); ?>",
                port: "<?php echo config::byKey('port', 'surveillanceStation'); ?>",
                https: "<?php echo config::byKey('https', 'surveillanceStation'); ?>",
                user: "<?php echo config::byKey('user', 'surveillanceStation'); ?>",
                password: "<?php echo config::byKey('password', 'surveillanceStation'); ?>",
                oauth: "<?php echo config::byKey('oauth', 'surveillanceStation'); ?>",
				snapLocation: "<?php echo config::byKey('snapLocation', 'surveillanceStation'); ?>",
				snapRetention: "<?php echo config::byKey('snapRetention', 'surveillanceStation'); ?>",
				default_snapRetention: "10",
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function (data) {
                if (data.state != 'ok') {
                    $('#div_alert').showAlert({message: data.result, level: 'danger'});
					return;
                }
				setTimeout( function() {
					location.reload();
				}, 100);
            }
        });
    }
    
</script>
