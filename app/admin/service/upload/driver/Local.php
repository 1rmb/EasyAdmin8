<?php

namespace app\admin\service\upload\driver;

use app\admin\service\upload\FileBase;
use app\admin\service\upload\trigger\SaveDb;

/**
 * 本地上传
 * Class Local
 * @package EasyAdmin\upload\driver
 */
class Local extends FileBase
{

    /**
     * 重写上传方法
     * @return array|void
     */
    public function save()
    {
        if (pathinfo($this->file->getOriginalName(), PATHINFO_EXTENSION) === 'php') {
            return [
                'save' => false,
                'msg'  => '上传文件中存在异常文件，请重新选择',
                'url'  => '',
            ];
        }
        parent::save();
        SaveDb::trigger($this->tableName, [
            'upload_type'   => $this->uploadType,
            'original_name' => $this->file->getOriginalName(),
            'mime_type'     => $this->file->getOriginalMime(),
            'file_ext'      => strtolower($this->file->getOriginalExtension()),
            'url'           => $this->completeFileUrl,
            'create_time'   => time(),
        ]);
        return [
            'save' => true,
            'msg'  => '上传成功',
            'url'  => $this->completeFileUrl,
        ];
    }

}