<?php
class HomeModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }
    public function registrar($title, $inicio, $color,$doctor,$paciente,$servicio,$status,$status_pago,$hora)
    {
        //estas modificando
        $sql = "INSERT INTO evento (title,start,color,doctor,paciente,servicio,status,status_pago,hora) VALUES (?,?,?,?,?,?,?,?,?)";
        $array = array($title, $inicio, $color,$doctor,$paciente,$servicio,$status,$status_pago,$hora);
        $data = $this->save($sql, $array);
        if ($data == 1) {
            $res = 'ok';
        }else{
            $res = 'error';
        }
        return $res;
    }
    
    public function getEventos()
    {
        $sql = "SELECT * FROM evento WHERE status!='7' and status!='4'";
        return $this->selectAll($sql);
    }

    public function modificar($title, $inicio, $color,$doctor,$paciente,$servicio,$status,$hora, $id)
    {
       // echo 'Estas Modificando';
        $sql = "UPDATE evento SET title=?, start=?, color=?, doctor=?, paciente=?, servicio=?, status=?, hora=? WHERE id=?";
        $array = array($title, $inicio, $color,$doctor,$paciente,$servicio,$status,$hora,$id);
        $data = $this->save($sql, $array);
        if ($data == 1) {
            $res = 'ok';
        } else {
            $res = 'error';
        }
        return $res;
    }
    public function eliminar($id)
    {
          $sql = "UPDATE evento SET status='4' WHERE id=?";
        $array = array($id);
        $data = $this->save($sql, $array);
        if ($data == 1) {
            $res = 'ok';
        } else {
            $res = 'error';
        }
        return $res;
    }
    public function dragOver($start, $id)
    {
        $sql = "UPDATE evento SET start=? WHERE id=?";
        $array = array($start, $id);
        $data = $this->save($sql, $array);
        if ($data == 1) {
            $res = 'ok';
        } else {
            $res = 'error';
        }
        return $res;
    }
//MODULO INTELIGENTE
    public function AsignarHoraOver($id_serv,$tanda)
    {
//----
if($tanda==4)
$sql="CALL AsignarHora('$id_serv', @horaAleatoria,@id_doc)";
else $sql="CALL AsignarHoraXTanda('$id_serv','$tanda', @horaAleatoria,@id_doc)";
$data = $this->SelectProc($sql);
        return $data;
    }
}

?>