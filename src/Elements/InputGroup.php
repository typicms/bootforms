<?php

namespace TypiCMS\BootForms\Elements;

use TypiCMS\Form\Elements\Text;

class InputGroup extends Text
{
    protected array $beforeAddon = [];

    protected array $afterAddon = [];

    public function beforeAddon(string $addon): self
    {
        $this->beforeAddon[] = $addon;

        return $this;
    }

    public function afterAddon(string $addon): self
    {
        $this->afterAddon[] = $addon;

        return $this;
    }

    public function type(string $type): self
    {
        $this->attributes['type'] = $type;

        return $this;
    }

    protected function renderAddons(array $addons): string
    {
        $html = '';

        foreach ($addons as $addon) {
            $html .= $addon;
        }

        return $html;
    }

    public function render(): string
    {
        $html = '<div class="input-group">';
        $html .= $this->renderAddons($this->beforeAddon);
        $html .= parent::render();
        $html .= $this->renderAddons($this->afterAddon);

        return $html.'</div>';
    }
}
