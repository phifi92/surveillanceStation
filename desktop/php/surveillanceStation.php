<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('surveillanceStation');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br/>
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br/>
				<span >{{Configuration}}</span>
			</div>
			<div class="cursor eqLogicAction bt_syncEqLogicSurveillanceStation">
				<i class="fas fa-sync"></i>
				<br>
				<span>{{Synchronisation}}</span>
			</div>
		</div>
		<legend><i class="fas fa-camera"></i> {{Mes caméras de Surveillance Station}}</legend>
		<input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
		<div class="eqLogicThumbnailContainer">
			<?php
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
				echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
				echo '<br/>';
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '</div>';
			}
			?>
		</div>
	</div>

  <div class="col-xs-12 eqLogic" style="display: none;">
			<div class="input-group pull-right" style="display:inline-flex">
				<span class="input-group-btn">
					<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a>
					<a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
					<a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
				</span>
			</div>
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
				<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
				<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
				<li role="presentation"><a href="#infoconfigtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Informations et configurations}}</a></li>
			</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br>
				<form class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{Nom de l'équipement template}}</label>
							<div class="col-sm-3">
								<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
								<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement template}}"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" >{{Objet parent}}</label>
							<div class="col-sm-3">
								<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
									<option value="">{{Aucun}}</option>
									<?php
									foreach (jeeObject::all() as $jeeObject) {
										echo '<option value="' . $jeeObject->getId() . '">' . $object->getName() . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="commandtab"><br>
				<div class="alert alert-info">Exemple d’URL à appeler : <?php echo network::getNetworkAccess('external') ?>/core/api/jeeApi.php?api=<?php echo jeedom::getApiKey('surveillanceStation'); ?>&type=surveillancestation&type=cmd&id=#ID_CMD</div>
				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>#</th>
							<th>{{Nom}}</th>
							<th>{{Type}}</th>
							<th>{{Options}}</th>
							<th>{{Action}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<div role="tabpanel" class="tab-pane" id="infoconfigtab"><br/>
				<div class="row">
					<div class="col-sm-6">
						<form class="form-horizontal">
							<fieldset>
								<legend>{{Informations de la caméra}}</legend>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="padding-top: 0px;">{{adresse IP}}</label>
									<div class="col-sm-2">
										<span class="eqLogicAttr" data-l1key="configuration" data-l2key="ip" style="font-size : 1em"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="padding-top: 0px;">{{ID Surveillance Station}}</label>
									<div class="col-sm-2">
										<span class="eqLogicAttr" data-l1key="configuration" data-l2key="id" style="font-size : 1em"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="padding-top: 0px;">{{Fabricant}}</label>
									<div class="col-sm-2">
										<span class="eqLogicAttr" data-l1key="configuration" data-l2key="vendor" style="font-size : 1em"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="padding-top: 0px;">{{Modele}}</label>
									<div class="col-sm-2">
										<span class="eqLogicAttr" data-l1key="configuration" data-l2key="model" style="font-size : 1em"></span>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				<div class="col-sm-6">
					<form class="form-horizontal">
						<fieldset>
							<legend>{{Compatibilités PTZ de la caméra}}</legend>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="padding-top: 0px;">{{Direction}}</label>
									<div class="col-sm-2">
										<span class="eqLogicAttr" data-l1key="configuration" data-l2key="ptzdirection" style="font-size : 1em"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="padding-top: 0px;">{{Home}}</label>
									<div class="col-sm-2">
										<span class="eqLogicAttr" data-l1key="configuration" data-l2key="ptzHome" style="font-size : 1em"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="padding-top: 0px;">{{Vitesse (Speed)}}</label>
									<div class="col-sm-2">
										<span class="eqLogicAttr" data-l1key="configuration" data-l2key="ptzSpeed" style="font-size : 1em"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="padding-top: 0px;">{{Panoramique}}</label>
									<div class="col-sm-2">
										<span class="eqLogicAttr" data-l1key="configuration" data-l2key="ptzPan" style="font-size : 1em"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="padding-top: 0px;">{{Inclinaison}}</label>
									<div class="col-sm-2">
										<span class="eqLogicAttr" data-l1key="configuration" data-l2key="ptzTilt" style="font-size : 1em"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="padding-top: 0px;">{{Zoom}}</label>
									<div class="col-sm-2">
										<span class="eqLogicAttr" data-l1key="configuration" data-l2key="ptzZoom" style="font-size : 1em"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="padding-top: 0px;">{{Absolute}}</label>
									<div class="col-sm-2">
										<span class="eqLogicAttr" data-l1key="configuration" data-l2key="ptzAbs" style="font-size : 1em"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" style="padding-top: 0px;">{{AutoFocus}}</label>
									<div class="col-sm-2">
										<span class="eqLogicAttr" data-l1key="configuration" data-l2key="ptzAutoFocus" style="font-size : 1em"></span>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<form class="form-horizontal">
							<fieldset>
								<legend>{{Configurations}}</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Activation du Live}}</label>
									<div class="col-sm-3">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="choixlive" checked/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Affiche les boutons de commandes}}</label>
									<div class="col-sm-3">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="choixactions" checked/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Affiche les statuts}}</label>
									<div class="col-sm-3">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="choixstatuts" checked/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Vitesse PTZ}}</label>
									<div class="col-sm-3">
										<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="speedptz">
											<option value="1" selected>{{1 (Défaut)}}</option>
											<option value="2">{{2}}</option>
											<option value="3">{{3}}</option>
											<option value="4">{{4}}</option>
											<option value="5">{{5}}</option>
										</select>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include_file('desktop', 'surveillanceStation', 'js', 'surveillanceStation');?>
<?php include_file('core', 'plugin.template', 'js');?>
