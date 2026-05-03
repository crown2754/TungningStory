<?php

namespace App\Livewire\Government;

use App\Models\Government;
use App\Models\Npc;
use Livewire\Component;

class GovernmentIndex extends Component
{
    public int $dialogueIndex = 0;
    public int $bubbleShake = 0;
    public string $currentDialogue = '';

    protected array $defaultDialogues = [
        '政事堂有令：商旅通市，須守法度，毋得囤積居奇。',
        '近來海路不靖，諸商號行船務必備齊糧水與護衛。',
        '稅收取之於民，當用之於民，務使東寧軍民同安。',
        '屯墾與軍備，乃立國之本，諸位商賈亦是國之棟梁。',
        '若見糧價異動異常，速報政事堂，以保市面穩定。',
    ];

    public function mount(): void
    {
        $koxinga = $this->getKoxingaNpc();
        $dialogues = $this->buildDialogues($koxinga);
        $this->currentDialogue = $dialogues[0];
    }

    public function talkToKoxinga(): void
    {
        $koxinga = $this->getKoxingaNpc();
        $dialogues = $this->buildDialogues($koxinga);

        $this->dialogueIndex = ($this->dialogueIndex + 1) % count($dialogues);
        $this->currentDialogue = $dialogues[$this->dialogueIndex];
        $this->bubbleShake++;
    }

    private function getKoxingaNpc(): ?Npc
    {
        return Npc::with('avatar')
            ->where('name', '鄭成功')
            ->where('is_active', true)
            ->first();
    }

    private function buildDialogues(?Npc $koxinga): array
    {
        $dialogues = $this->defaultDialogues;

        if ($koxinga?->greeting) {
            array_unshift($dialogues, $koxinga->greeting);
        }

        return array_values(array_unique($dialogues));
    }

    public function render()
    {
        $government = Government::current();
        $koxinga = $this->getKoxingaNpc();

        return view('livewire.government.government-index', [
            'gov' => $government,
            'koxinga' => $koxinga,
        ])->layout('layouts.app');
    }
}
