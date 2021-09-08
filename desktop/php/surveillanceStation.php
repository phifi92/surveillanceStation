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

	<!-- Page de présentation de l'équipement -->
    <div class="col-xs-12 eqLogic" style="display: none;">
        <!-- barre de gestion de l'équipement -->
        <div class="input-group pull-right" style="display:inline-flex;">
			<span class="input-group-btn">
				<!-- Les balises <a></a> sont volontairement fermées à la ligne suivante pour éviter les espaces entre les boutons. Ne pas modifier -->
				<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span>
				</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}
				</a>
			</span>
		</div>
        <!-- Onglets -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
            <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
            <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
        </ul>
        <div class="tab-content">
            <!-- Onglet de configuration de l'équipement -->
            <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                <br/>
                <div class="row">
					<!-- Partie gauche de l'onglet "Equipements" -->
					<!-- Paramètres généraux de l'équipement -->
					<div class="col-lg-7">
                        <form class="form-horizontal">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Nom de l'équipement}}</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                                        <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                                    <div class="col-sm-3">
                                        <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                                            <option value="">{{Aucun}}</option>
                                            <?php
                                            foreach (jeeObject::all() as $object) {
                                                echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Catégorie}}</label>
                                    <div class="col-sm-9">
                                        <?php
                                            foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                                echo '<label class="checkbox-inline">';
                                                echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                                                echo '</label>';
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"></label>
                                    <div class="col-sm-9">
                                        <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                                        <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                                    </div>
                                </div>
                                <br/>
                                <div><legend><i class="fas fa-cog"></i> {{Configuration}}</legend></div>
                                <!-- Template for Centrale -->
                                <div>
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
                                </div>
								<br/>
                                <div><legend><i class="fas fa-desktop"></i> {{Affichage}}</legend></div>
                                <div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">{{Activation du Live}}</label>
                                        <div class="col-sm-3">
                                            <input type="checkbox" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="choixlive" checked/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">{{Affiche les boutons de commandes}}</label>
                                        <div class="col-sm-3">
											<input type="checkbox" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="choixactions" checked/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">{{Affiche les statuts}}</label>
                                        <div class="col-sm-3">
											<input type="checkbox" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="choixstatuts" checked/>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
					<!-- Partie droite de l'onglet "Equipement" -->
					<!-- Affiche l'icône du plugin par défaut mais vous pouvez y afficher les informations de votre choix -->
					<div class="col-lg-5">
						<form class="form-horizontal">
							<fieldset>
								<legend><i class="fas fa-info"></i> {{Informations}}</legend>
								<div class="form-group">
									<label class="col-sm-3"></label>
									<div class="col-sm-7 text-center">
										<img name="icon_visu" src="<?= $plugin->getPathImgIcon(); ?>" style="max-width:160px;"/>
									</div>
								</div>
								<br/>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Adresse IP}}</label>
									<div class="col-sm-7">
										<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="ip"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{ID Surveillance Station}}</label>
									<div class="col-sm-7">
										<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="id"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Fabricant}}</label>
									<div class="col-sm-7">
										<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="vendor"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Modele}}</label>
									<div class="col-sm-7">
										<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="model"></span>
									</div>
								</div>
								<br/>
								<legend><i class="fas fa-video"></i> {{Compatibilités PTZ de la caméra}}</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Direction}}</label>
									<div class="col-sm-7">
										<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="ptzdirection"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Home}}</label>
									<div class="col-sm-7">
										<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="ptzHome"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Vitesse (Speed)}}</label>
									<div class="col-sm-7">
										<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="ptzSpeed"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Panoramique}}</label>
									<div class="col-sm-7">
										<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="ptzPan"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Inclinaison}}</label>
									<div class="col-sm-7">
										<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="ptzTilt"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Zoom}}</label>
									<div class="col-sm-7">
										<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="ptzZoom"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Absolute}}</label>
									<div class="col-sm-7">
										<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="ptzAbs"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{AutoFocus}}</label>
									<div class="col-sm-7">
										<span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="ptzAutoFocus"></span>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
                </div><!-- /.row-->
            </div>

            <!-- Onglet des commandes de l'équipement -->
			<div role="tabpanel" class="tab-pane" id="commandtab">
				<a class="btn btn-default btn-sm pull-right cmdAction" data-action="add" style="margin-top:5px;"><i class="fas fa-plus-circle"></i> {{Ajouter une commande}}</a>
				<br/><br/>
				<div class="table-responsive">
					<table id="table_cmd" class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th>{{Id}}</th>
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
			</div><!-- /.tabpanel #commandtab-->

        </div><!-- /.tab-content -->
	</div><!-- /.eqLogic -->
</div><!-- /.row row-overflow -->

<!-- Inclusion du fichier javascript du plugin (dossier, nom_du_fichier, extension_du_fichier, id_du_plugin) -->
<?php include_file('desktop', 'surveillanceStation', 'js', 'surveillanceStation');?>
<!-- Inclusion du fichier javascript du core - NE PAS MODIFIER NI SUPPRIMER -->
<?php include_file('core', 'plugin.template', 'js');?>
