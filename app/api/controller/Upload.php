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
        // 获取上传文件
        $file = $this->request->file('file');
        
        if (empty($file)) {
            return $this->error('请选择上传文件');
        }

        try {
            // // 验证文件大小和后缀
            // validate(['file' => [
            //     'fileSize' => 1024 * 1024 * 2, // 2MB
            //     'fileExt'  => 'jpg,jpeg,png,gif',
            //     'fileMime' => 'image/jpeg,image/png,image/gif',
            // ]])->check(['file' => $file]);

            // 保存文件到 public 磁盘的 uploads/images 目录下
            $saveName = Filesystem::disk('public')->putFile('uploads', $file);
            
            // 获取文件的完整 URL
            $url = $this->request->domain() . '/storage/' . str_replace('\\', '/', $saveName);

            return $this->success([
                'url'  => $url,
                'path' => '/storage/' . str_replace('\\', '/', $saveName),
            ], '上传成功');

        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (\Exception $e) {
            return $this->error('上传失败：' . $e->getMessage());
        }
    }
}
