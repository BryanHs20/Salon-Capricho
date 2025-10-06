<?php require_once('db-connect.php') ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Capricho</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="../Assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Assets/css/main.min.css">
    <link rel="stylesheet" href="../Assets/css/agenda.css">
    <script src="../Assets/js/jquery-3.6.0.min.js"></script>
    <script src="../Assets/js/bootstrap.min.js"></script>
    <script src="../Assets/js/main.min.js"></script>    
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient" id="topNavBar">
        <div class="container">
            <a class="navbar-brand" href="../">
            Inicio
            </a>

            <div>
                <b class="text-light">Calendario de Eventos</b>
            </div>
        </div>
    </nav>
    <div class="container py-5" id="page-container">
        <div class="row">
            <div class="col-md-9">
                <div id="calendar"></div>
            </div>
            <div class="col-md-3">
                <div class="cardt rounded-0 shadow">
                    <div class="card-header bg-gradient bg-primary text-light">
                        <h5 class="card-title">Crear Evento</h5>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <form action="save_schedule.php" method="post" id="schedule-form">
                                <input type="hidden" name="id" value="">
                                <div class="form-group mb-2">
                                    <label for="title" class="control-label">Evento</label>
                                    <input type="text" class="form-control form-control-sm rounded-0" placeholder="Tipo o Nombre del evento" name="title" id="title" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="monto" class="control-label">Monto</label>
                                    <input type="number" class="form-control form-control-sm rounded-0" placeholder="Monto total a pagar" name="monto" id="monto" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="pagos" class="control-label">Pagos <abbr title="Si se liquido en un solo pago, colocar 'Liquidado', en caso contrario, colocar los montos a pagar">⚠️</abbr></label>
                                    <input type="text" class="form-control form-control-sm rounded-0" placeholder="Montos o Liquidados" name="pagos" id="pagos" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="notas" class="control-label">Notas</label>
                                    <textarea rows="3" class="form-control form-control-sm rounded-0" placeholder="Caracteristicas o comentarios para el evento" name="notas" id="notas" required></textarea>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="start_datetime" class="control-label">Dia y Hora de Inicio</label>
                                    <input type="date" class="form-control form-control-sm rounded-0" name="start_datetime" id="start_datetime" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="end_datetime" class="control-label">Hora de Termino</label>
                                    <input type="date" class="form-control form-control-sm rounded-0" name="end_datetime" id="end_datetime" required>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-center">
                            <button class="btn btn-primary btn-sm rounded-0" type="submit" form="schedule-form"><i class="fa fa-save"></i> Guardar</button>
                            <button class="btn btn-default border btn-sm rounded-0" type="reset" form="schedule-form"><i class="fa fa-reset"></i> Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Event Details Modal -->
    <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-header rounded-0">
                    <h5 class="modal-title">Detalles de evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body rounded-0">
                    <div class="container-fluid">
                        <dl>
                            <dt class="text-muted">Nombre</dt>
                            <dd id="title" class="fw-bold fs-4"></dd>
                            <dt class="text-muted">Notas</dt>
                            <dd id="notas" class=""></dd>
                            <dt class="text-muted">Monto</dt>
                            <dd id="monto" class=""></dd>
                            <dt class="text-muted">Pagos</dt>
                            <dd id="pagos" class=""></dd>
                            <dt class="text-muted">Dia y Hora de Inicio</dt>
                            <dd id="start" class=""></dd>
                            <dt class="text-muted">Hora de Termino</dt>
                            <dd id="end" class=""></dd>
                        </dl>
                    </div>
                </div>
                <div class="modal-footer rounded-0">
                    <div class="text-end">
                        <button type="button" class="btn btn-primary btn-sm rounded-0" id="edit" data-id="">Editar</button>
                        <button type="button" class="btn btn-danger btn-sm rounded-0" id="delete" data-id="">Eliminar</button>
                        <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Event Details Modal -->

<?php 
$schedules = $conn->query("SELECT * FROM `schedule_list`");
$sched_res = [];
foreach($schedules->fetch_all(MYSQLI_ASSOC) as $row){
    $row['sdate'] = date("F d, Y h:i A",strtotime($row['start_datetime']));
    $row['edate'] = date("F d, Y h:i A",strtotime($row['end_datetime']));
    $sched_res[$row['id']] = $row;
}
?>
<?php 
if(isset($conn)) $conn->close();
?>
</body>

<script src="../Assets/js/es.js"></script> <!--Idioma español Fullcalendar-->
<script>
    var scheds = $.parseJSON('<?= json_encode($sched_res) ?>')
</script>
<script src="../Assets/js/script.js"></script>

</html>