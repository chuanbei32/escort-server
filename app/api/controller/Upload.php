<?php
declare (strict_types = 1);

namespace app\api\controller;

use think\exception\ValidateException;
use think\facade\Filesystem;

class Upload extends Base
{
    /**
     * 上传图片
     *
     * @return \think\Response
     */
    public function file()
    {
        error_reporting(E_ALL & ~E_DEPRECATED);

        // 获取上传文件
        $file = $this->request->file('file');
        
        try {
            $this->validate(['file' => $file], \app\api\validate\Upload::class . '.file');

            // 保存文件到 public 磁盘的 uploads 目录下
            $saveName = Filesystem::disk('public')->putFile('uploads', $file);
            
            // 获取文件的完整 URL
            $url = $this->request->domain() . '/storage/' . str_replace('\\', '/', $saveName);

            return $this->success([
                'url'  => $url,
                'path' => '/storage/' . str_replace('\\', '/', $saveName),
            ], '上传成功');

        } catch (ValidateException $e) {
            return $this->error($e->getError());
        } catch (\Exception $e) {
            return $this->error('上传失败：' . $e->getMessage());
        }
    }
}
