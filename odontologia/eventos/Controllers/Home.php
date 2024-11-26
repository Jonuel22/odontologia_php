<?php
class Home extends Controller
{
    public function __construct() {
        parent::__construct();
    }
    public function index()
    {
        $this->views->getView($this, "index");
    }
    public function registrar()
    {
        if (isset($_POST)) {
            if (empty($_POST['title']) || empty($_POST['start'])) {
            
            }else{
                $title = $_POST['title'];
                $id = $_POST['id'];
                $start = $_POST['start'];
                $color = $_POST['color'];
                $doctor=$_POST['doctor'];
                $paciente=$_POST['paciente'];
                $servicio=$_POST['servicio'];
                $status=5;
                $status_pago=3;
                $hora=$_POST['hora'];                
                if ($id == '') {
                    $data = $this->model->registrar($title, $start, $color,$doctor,$paciente,$servicio,$status,$status_pago,$hora);
                    if ($data == 'ok') {
                        $msg = array('msg' => 'Evento Registrado', 'estado' => true, 'tipo' => 'success');
                    }else{
                        $msg = array('msg' => 'Error al Registrar', 'estado' => false, 'tipo' => 'danger');
                    }
                } else {
                    $data = $this->model->modificar($title, $start, $color,$doctor,$paciente,$servicio,$status,$hora,$id);
                    if ($data == 'ok') {
                        $msg = array('msg' => 'Evento Modificado', 'estado' => true, 'tipo' => 'success');
                    } else {
                        $msg = array('msg' => 'Error al Modificar', 'estado' => false, 'tipo' => 'danger');
                    }
                }
                
            }
            echo json_encode($msg);
        }
        die();
    }
    public function listar()
    {
        $data = $this->model->getEventos();
        echo json_encode($data);
        die();
    }
    public function eliminar($id)
    {
        $data = $this->model->eliminar($id);
        if ($data == 'ok') {
            $msg = array('msg' => 'Evento Eliminado', 'estado' => true, 'tipo' => 'success');
        } else {
            $msg = array('msg' => 'Error al Eliminar', 'estado' => false, 'tipo' => 'danger');
        }
        echo json_encode($msg);
        die();
    }
    public function drag()
    {
        if (isset($_POST)) {
            if (empty($_POST['id']) || empty($_POST['start'])) {
                $msg = array('msg' => 'Todo los campos son requeridos', 'estado' => false, 'tipo' => 'danger');
            } else {
                $start = $_POST['start'];
                $id = $_POST['id'];
                $data = $this->model->dragOver($start, $id);
                if ($data == 'ok') {                    
                    $msg = array('msg' => 'Evento Modificado', 'estado' => true, 'tipo' => 'success');
                } else {
                    $msg = array('msg' => 'Error al Modificar', 'estado' => false, 'tipo' => 'danger');
                }
            }
            echo json_encode($msg);
        }
        die();
    }

    //MODULO INTELIGENTE---------------------
      public function AsignarHora()
    {
        if (isset($_POST)) {
            if (empty($_POST['id_serv'])||empty($_POST['tanda'])) {
                
             //   $msg = array('msg' => 'Debe introducir el servicio', 'estado' => false, 'tipo' => 'danger');
            } else {
                $id_serv = $_POST['id_serv'];
                $tanda=$_POST['tanda'];              
                $data = $this->model->AsignarHoraOver($id_serv,$tanda);
                $data[0]=DateTime::createFromFormat('!H:i:s', trim($data[0]))->format('H:i');
                $msg = array('hora' => $data[0], 'doctor' => $data[1]);
                echo json_encode($msg);
            }
        }
        die();
    }
    // ----------------------
}
