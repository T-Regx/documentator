<?php
namespace Documentary;

class Comments
{
    private array $comments = [];

    public function get(string $name, string $type, ?string $parent): ?string
    {
        return
            $this->comments["$name:$type:$parent"] ??
            $this->comments["$name:$type:"] ??
            $this->comments["$name::$parent"] ??
            $this->comments["$name::"] ??
            null;
    }

    public function add(string $name, ?string $type, ?string $parent, string $comment): void
    {
        $this->put("$name:$type:$parent", $name, $comment);
    }

    private function put(string $key, string $name, string $comment): void
    {
        if (\array_key_exists($key, $this->comments)) {
            throw new \Exception("Failed to document element '$name' with multiple summaries.");
        }
        $this->comments[$key] = $comment;
    }
}
