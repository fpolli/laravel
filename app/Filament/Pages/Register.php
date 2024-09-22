<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;

class Register extends Page
{
  protected static ?string $navigationIcon = 'heroicon-o-document-text';

  protected static string $view = 'filament.pages.register';

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        //

      ]);
  }
}
