<?php 

namespace App\Traits;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

trait FileManipulationTrait{

	protected $fileDisk ='public';
	/**
	 * [setUploadDisk : Disk Setter]
	 * @param [type] $disk [local/s3/rackspace]
	 */
	public function setUploadDisk($disk){
		$this->fileDisk=$disk;
		return $this;
	}
	/**
	 * [getUploadDisk : Fetch Disc Name]
	 * @return [type] [description]
	 */
	public function getUploadDisk(){
		return $this->fileDisk;
	}

	/**
	 * [setFilePrivacy : Set file Privacy]
	 * @param [type] $privacy [public/private]
	 */
	public function setFilePrivacy($filePath,$privacy='public'){
		return Storage::setVisibility("$filePath", "$privacy");

	}

	/**
	 * [getOrginalFileName : Fetching file original Name]
	 * @param  [FILE OBJ] $file [description]
	 * @return [STRING]       [Name Of the File]
	 */
	public function getOrginalFileName($file){
		return $file->getClientOriginalName();
	}

	/**
	 * [getFileExtension : Fetch File Extenstion]
	 * @param  [FILE OBJ] $file [description]
	 * @return [STRING]       [extension of the file]
	 */
	public function getFileExtension($file){
		return $file->getClientOriginalExtension();
	}

	/**
	 * [generateFileName : File name generator based on current Name]
	 * @param  [FILE OBject] $file [description]
	 * @return [String]       [New Name for the file]
	 */
	public function generateFileName($file){
		$fileOrgName=clean($this->getOrginalFileName($file));
		$fileExt=$this->getFileExtension($file);
		$fileName=$fileOrgName."_".time().'.'.$fileExt;
		if(strlen($fileName)>=255){
			$fileName=time().'.'.$fileExt;
		}
		return $fileName;
	}

	/**
	 * [quickUpload description]
	 * @param  [FILE] $fileObj   [description]
	 * @param  [STRING] $directory [description]
	 * @return [STRING]            [path of the file]
	 */
	public function quickUpload($fileObj, $directory){
		$path = $fileObj->store($directory,$this->getUploadDisk());
        return $path;
	}

	/**
	 * [uploadAs description]
	 * @param  [type] $fileObj   [description]
	 * @param  [type] $directory [description]
	 * @param  string $fileName  [description]
	 * @return [type]            [description]
	 */
	public function uploadAs($fileObj,$directory,$fileName=""){
		$name=($fileName=="")? $this->generateFileName($fileObj): $fileName;
		$path = $fileObj->storeAs($directory, $name, $this->getUploadDisk());
		return $path;
	}

	/**
	 * [getFileUrl : Get FIle URL]
	 * @param  [STRING] $fileName [Path of the fille]
	 * @return [STRING]           [URL]
	 */
	public function getFileUrl($fileName){
		$url = Storage::url($fileName);
		return asset($url);
	}

	/**
	 * [destoryFile : Remove file]
	 * @param  [STRING] $file [File name with path]
	 * @return [BOOLEAN]       [description]
	 */
	public function destoryFile($file){
		return Storage::delete("$file");
	}

	/**
	 * [destoryFiles : Remove Multiple files]
	 * @param  [ARRAY] $file [ARRAY OF File name with path]
	 * @return [BOOLEAN]       [description]
	 */
	public function destoryFiles($files){
		return Storage::delete($files);
	}

	/**
	 * ############ DIRECTORY FUNCTIONS ############
	 */
	
	/**
	 * [getFolderFiles : Get the all files in the particular directory]
	 * @param  [STRING] $directory [Directory path]
	 * @return [ARRAY]            [Array of files Name]
	 */
	public function getFolderFiles($directory){
		return Storage::allFiles($directory);
	}

	/**
	 * [getDirectories : Get Inner Direcories]
	 * @param  [STRING] $directory [Name of directory with path : After storage/app/]
	 * @return [ARR]            [Get all sub-directories]
	 */
	public function getDirectories($directory){
		return Storage::directories($directory);
	}

	/**
	 * [createDir : Make A directory inside storage folder]
	 * @param  [name of directory] $directory [description]
	 * @return [STRING]            [Path of directory]
	 */
	public function createDir($directory){
		return Storage::makeDirectory($directory);
	}

	/**
	 * [destroyDirectory : Distroy Particular DIr]
	 * @param  [STRING] $directory [Path of directory]
	 * @return [type]            [description]
	 */
	public function destroyDirectory($directory){
		Storage::deleteDirectory("$directory");
	}



}