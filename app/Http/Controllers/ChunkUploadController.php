<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChunkUploadController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('file');
        
        $chunkIndex = $request->input('resumableChunkNumber');
        $totalChunks = $request->input('resumableTotalChunks');
        $filename = $request->input('resumableFilename');
        
        // Identificador único para este archivo temporal
        $identifier = $request->input('resumableIdentifier');
        $tempFolder = 'chunks/' . $identifier;

        // Guardar el chunk actual de forma temporal
        $chunkName = $chunkIndex . '_' . $filename;
        Storage::disk('local')->putFileAs($tempFolder, $file, $chunkName);

        // Comprobar si ya se subieron todos los fragmentos
        if ($chunkIndex == $totalChunks) {
            // Crear el archivo final uniendo los pedazos
            $finalPath = 'uploads/' . uniqid() . '_' . $filename;
            
            // Asegurar que el directorio de destino exista
            Storage::disk('public')->makeDirectory('uploads');
            $absoluteFinalPath = Storage::disk('public')->path($finalPath);
            
            $fileHandle = fopen($absoluteFinalPath, 'ab');

            for ($i = 1; $i <= $totalChunks; $i++) {
                $chunkPath = Storage::disk('local')->path($tempFolder . '/' . $i . '_' . $filename);
                $chunkContent = file_get_contents($chunkPath);
                fwrite($fileHandle, $chunkContent);
                
                // Borrar el chunk temporal para liberar espacio
                unlink($chunkPath);
            }

            fclose($fileHandle);
            rmdir(Storage::disk('local')->path($tempFolder));

            // Retornamos el path relativo para que Filament lo guarde en la BD
            return response()->json([
                'path' => $finalPath,
                'status' => 'completed'
            ]);
        }

        return response()->json(['status' => 'chunk_uploaded']);
    }
}