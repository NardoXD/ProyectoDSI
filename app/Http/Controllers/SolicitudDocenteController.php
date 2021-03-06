<?php

namespace App\Http\Controllers;

use App\Anexo;
use App\DetalleSolicitud;
use App\Estado;
use App\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitudDocenteController extends Controller
{
    private $incapacidad = 9;
    private $misionOficial=10;
    private $denunciaCEstu = 11;
    private $reclasificacion = 12;
    private $petiOficiales = 13;
    private $petiOtros = 14;

    /**
     * CONTRALADORES PETICIONES LICENCIA INCAPACIDAD
     */

    public function licenciaCrear(){
        $user = Auth::user();
        $persona = $user->persona;
        return view('docente.licencia', ['persona' => $persona]);
    }


    public function licenciaStore(Request $request){
        $this->validate($request, [
            'fechaInicio' => 'required|date|after:yesterday',
            'fechaFin' => 'required|date|after:yesterday',
            'anexo' => 'required|max:2018',
        ]);


        $solicitud = Solicitud::create([
            'userId' => Auth::id(),
            'estadoId' => Estado::all()[0]->id,
            'tipoSolicitudId' => $this->incapacidad,
        ]);

        $detalle = new DetalleSolicitud();
        $detalle->solicitudId = $solicitud->id;
        $detalle->fechaInicio = $request['fechaInicio'];
        $detalle->fechaFinalizacion = $request['fechaFin'];
        $detalle->save();

        $files = $request->file('anexo');
        foreach ($files as $file) {
            $guardado=$file->store('public');

            $anexo = new Anexo();
            $anexo->nombreOriginal = $file->getClientOriginalName();
            $anexo->ruta = $guardado;
            $anexo->detalleSolicitudId = $detalle->id;
            $anexo->save();
        }

        $user = Auth::user();
        $persona = $user->persona;
        return redirect('docente/licencia-incapacidad/crear')->with('status', 'Peticion Enviada con exito')->with('persona', $persona);
    }    
    
    /**
     * CONTRALADORES PETICIONES MISIONES OFICIALES
     */

    public function misionOficialCrear(){
        $user = Auth::user();
        $persona = $user->persona;
        return view('docente.misiones', ['persona' => $persona]);
    }


    public function misionOficialStore(Request $request){
        $this->validate($request, [
            'fechaInicio' => 'required|date|after:yesterday',
            'fechaFin' => 'required|date|after:yesterday',
            'anexo' => 'required|max:2018',
        ]);


        $solicitud = Solicitud::create([
            'userId' => Auth::id(),
            'estadoId' => Estado::all()[0]->id,
            'tipoSolicitudId' => $this->misionOficial,
        ]);

        $detalle = new DetalleSolicitud();
        $detalle->solicitudId = $solicitud->id;
        $detalle->fechaInicio = $request['fechaInicio'];
        $detalle->fechaFinalizacion = $request['fechaFin'];
        $detalle->save();

        $files = $request->file('anexo');
        foreach ($files as $file) {
            $guardado=$file->store('public');

            $anexo = new Anexo();
            $anexo->nombreOriginal = $file->getClientOriginalName();
            $anexo->ruta = $guardado;
            $anexo->detalleSolicitudId = $detalle->id;
            $anexo->save();
        }

        $user = Auth::user();
        $persona = $user->persona;
        return redirect('docente/misiones-oficiales/crear')->with('status', 'Peticion Enviada con exito')->with('persona', $persona);
    }
    /**
     * CONTRALADORES PETICIONES DENUNCIAS CONTRA ESTUDIANTES
     */

    public function denunciaCrear(){
        $user =  Auth::user();
        $persona = $user->persona;
        return view('docente.denuncia', ['persona' => $persona]);
    }


    public function denunciaStore(Request $request){
        $this->validate($request, [
            'anexo' => 'required|max:2018',
        ]);

        $solicitud = Solicitud::create([
            'userId' => Auth::id(),
            'estadoId' => Estado::all()[0]->id,
            'tipoSolicitudId' => $this->denunciaCEstu,
        ]);

        $detalle = new DetalleSolicitud();
        $detalle->solicitudId = $solicitud->id;
        $detalle->save();

        $files = $request->file('anexo');
        foreach ($files as $file) {
            $guardado=$file->store('public');

            $anexo = new Anexo();
            $anexo->nombreOriginal = $file->getClientOriginalName();
            $anexo->ruta = $guardado;
            $anexo->detalleSolicitudId = $detalle->id;
            $anexo->save();
        }

        $user =  Auth::user();
        $persona = $user->persona;
        return redirect('docente/denuncias-c-estudiantes/crear')->with('status', 'Peticion Enviada con exito')->with('persona', $persona);
    }

    /**
     * CONTRALADORES PETICIONES RECLASIFICACION
     */

    public function reclasificacionCrear(){
        $user = Auth::user();
        $persona = $user->persona;
        return view('docente.reclasificacion')->with('persona', $persona);
    }


    public function reclasificacionStore(Request $request){
        $this->validate($request, [
            'nivelActual' => 'required|numeric|regex:/^[123]$/',
            'nivelDeseado' => 'required|numeric|different:nivelActual|regex:/^[123]$/',
            'fechaFin' => 'required|date|after:today',
            'justificacion' => 'required|string|regex:/^([a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
        ]);

        $solicitud = Solicitud::create([
            'userId' => Auth::id(),
            'estadoId' => Estado::all()[0]->id,
            'tipoSolicitudId' => $this->reclasificacion,
        ]);

        $detalle = new DetalleSolicitud();
        $detalle->solicitudId = $solicitud->id;
        $detalle->nivelAcademicaActual = $request['nivelActual'];
        $detalle->nivelAcademicaAspira = $request['nivelDeseado'];
        $detalle->fechaFinalizacion = $request['fechaFin'];
        $detalle->justificacion = $request['justificacion'];
        $detalle->save();

        $user =  Auth::user();
        $persona = $user->persona;
        return redirect('docente/reclasificacion/crear')->with('status', 'Peticion Enviada con exito')->with('persona', $persona);
    }
    
    /**
     * CONTRALADORES PETICIONES ESPECIALES
     */

    public function peticionEspecialCrear(){
        $user =  Auth::user();
        $persona = $user->persona;
        return view('docente.peticiones', ['persona' => $persona]);
    }


    public function peticionEspecialStore(Request $request){
        $this->validate($request, [
            'anexo' => 'required|max:2018',
        ]);

        $solicitud = Solicitud::create([
            'userId' => Auth::id(),
            'estadoId' => Estado::all()[0]->id,
            'tipoSolicitudId' => $this->petiOficiales,
        ]);

        $detalle = new DetalleSolicitud();
        $detalle->solicitudId = $solicitud->id;
        $detalle->save();

        $files = $request->file('anexo');
        foreach ($files as $file) {
            $guardado = $file->store('public');
            $anexo = new Anexo();
            $anexo->nombreOriginal = $file->getClientOriginalName();
            $anexo->ruta = $guardado;
            $anexo->detalleSolicitudId = $detalle->id;
            $anexo->save();
        }

        $user =  Auth::user();
        $persona = $user->persona;
        return redirect('docente/peticiones-especiales/crear')->with('status', 'Peticion Enviada con exito')->with('persona', $persona);
    }


    /**
     * CONTROLADORES CONSULTA ESTUDIANTE
     */
    public function consultaIndex(){
        $user=Auth::user();
        $solicitudes=$user->solicituds;  
        return view('docente.consulta',['solicitudes'=>$solicitudes]);
    }


    public function consultaShow($id){
        $solicitud=Solicitud::find($id);
        return view('docente.consulta-show',["solicitud"=>$solicitud]);
    }        


        
        
}
