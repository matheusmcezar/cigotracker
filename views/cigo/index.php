<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Cigo Tracker - Matheus Cezar';

$prependValue = '{label}<div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>
                    {input}
                </div>';

$prependDate = '{label}<div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>
                    {input}
                </div>{error}';

?>
<div class="site-index">
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading" data-toggle="collapse" href="#panelBodyCollapse" style="cursor:pointer;">
                <i class="fa fa-plus"></i>
                Add an Order    
            </div>
            <div id="panelBodyCollapse" class="panel-body collapse">
                <?php $form = ActiveForm::begin(); ?>
                <div class="row">
                    <?= $form->field($order, 'firstName', ['options' => ['class' => 'col-md-6']])
                            ->label(null, ["class" => 'required'])
                            ->textInput(['placeholder'=>'First name','maxlength'=>30]) ?>

                    <?= $form->field($order, 'lastName', ['options' => ['class' => 'col-md-6']])
                            ->textInput(['placeholder'=>'Last name','maxlength'=>30]) ?>
                </div>
                <div class="row">
                    <?= $form->field($order, 'email', ['options' => ['class' => 'col-md-6']])
                            ->textInput(['placeholder'=>'you@sample.com','maxlength'=>30]) ?>

                    <?= $form->field($order, 'phoneNumber', ['options' => ['class' => 'col-md-6']])
                            ->label(null, ["class" => 'required'])
                            ->textInput(['placeholder'=>'+1 (234) 567-8900','maxlength'=>30,'class'=>'form-control telephone'])
                    ?>
                </div>
                <div class="row">
                    <?= $form->field($order, 'orderType', ['options' => ['class' => 'col-md-6']])
                            ->label(null, ["class" => 'required'])
                            ->dropDownList($orderTypes) ?>

                    <?= $form->field($order, 'orderValue', ['template'=>$prependValue, 'options' => ['class' => 'col-md-6']])
                            ->textInput(['placeholder'=>'Amount','class'=>'form-control currency','maxlength'=>14])
                    ?>
                </div>
                <div class="row">
                    <?= $form->field($order, 'scheduledDate', ['template'=>$prependDate, 'options' => ['class' => 'col-md-6']])
                            ->input("date", ['style' =>'padding-top: 0px'])->label(null, ["class" => 'required']) ?>
                </div>
                <div class="row">
                    <?= $form->field($order, 'streetAddress', ['options' => ['class' => 'col-md-12']])
                            ->label(null, ["class" => 'required'])
                            ->textInput(['maxlength'=>50,'class'=>'form-control address']) ?>
                </div>
                <div class="row">
                    <?= $form->field($order, 'city', ['options' => ['class' => 'col-md-6']])
                            ->label(null, ["class" => 'required'])
                            ->textInput(['maxlength'=>30,'class'=>'form-control address']) ?>

                    <?= $form->field($order, 'state', ['options' => ['class' => 'col-md-6']])
                            ->label(null, ["class" => 'required'])
                            ->textInput(['maxlength'=>30,'class'=>'form-control address']) ?>
                </div>
                <div class="row">
                    <?= $form->field($order, 'country', ['options' => ['class' => 'col-md-6']])
                            ->label(null, ["class" => 'required'])
                            ->dropDownList($countries,['class'=>'form-control address']) ?>

                    <?= $form->field($order, 'postalCode', ['options' => ['class' => 'col-md-6']])
                            ->textInput(['maxlength'=>15,'class'=>'form-control address']) ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <?= Html::resetButton('Cancel', ['id'=>'cancelButton','class' => 'btn btn-danger']) ?>
                            <input type="button" class="btn btn-default" value="Preview Location" onClick="previewMarker()" />
                            <?= Html::submitButton('Submit', ['id'=>'submitButton','class' => 'btn btn-success', 'disabled'=>'disabled']) ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-truck"></i>
                Existing Orders
            </div>
            <div class="panel-body" style="height: 417px">
                <table class="dataTable stripe cell-border hover" id="ordersTable">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-globe-americas"></i>
                Map
            </div>
            <div class="panel-body">
                <div id="divmap" style="height: 450px"></div>
            </div>
        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <spam style="font-size: 2rem; color: #333">Delete record</spam>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure abou deleting this record?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button id="deleteButton" data="0" onClick="deleteOrder(this)" type="button" class="btn btn-danger">Yes, delete</button>
            </div>
        </div>
    </div>
</div>

<div id="modalNotFound" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <spam style="font-size: 2rem; color: #333">Oops!</spam>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Address not found
            </div>
        <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

</div>
</div>
</div>

<!-- loader -->
<div id="loadingScreen" class="loadingScreen">
    <div class="loader"></div> 
</div>
