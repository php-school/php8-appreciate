<?php

class Image extends File
{
    private ?string $ext = null;
    private ?string $crop = null;

    public function withExt(string $ext): static
    {
        $clone = clone $this;
        $clone->ext = $ext;
        return $clone;
    }

    public function withCrop(string $crop): static
    {
        $clone = clone $this;
        $clone->crop = $crop;
        return $clone;
    }
}

$image = (new Image())->withPermissions('w+')->withExt('jpeg')->withCrop('16x9');
var_dump($image);