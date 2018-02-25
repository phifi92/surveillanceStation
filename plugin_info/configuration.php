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
		<div class="form-group">
			<label class="col-sm-4 control-label">{{Adresse DNS de votre Synolgy}}</label>
			<div class="col-sm-3">
				<input type="text" class="configKey form-control" data-l1key="ip" placeholder="NomDomaineDeMonSynology.tld"/>
			</div>
		</div>
		<div class="alert alert-info">
			Pour que le live soit accessible de l'extérieur, il est important de renseigner l'adresse DNS de votre Synology (MonSynology.tld).
			Cette même adresse doit aussi fonctionner de votre réseau local.<br>
			A contrario, avec un IP LAN, le Live sera accessible seulement de votre réseau local.</div>
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
	</fieldset>
</form>
