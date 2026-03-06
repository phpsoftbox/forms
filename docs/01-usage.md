# Forms

Компонент `Forms` хранит декларативные описания форм и умеет рендерить их в выбранный драйвер.

Текущее состояние:
- `json` драйвер реализован;
- `html` драйвер добавлен как заглушка (пока возвращает пустую строку).

## Базовые DTO

- `FormDefinition` — описание формы (`id`, `title`, `fields`, `meta`);
- `FormFieldDefinition` — описание поля (`key`, `label`, `fieldType`, `required`, `description`, `valueType`, `options`, `meta`, `requiredWhen`, `visibleWhen`, `suggest`, `server`);
- `FormSuggestDefinition` — декларация источника подсказок (`endpoint`, `queryParam`, `valueKey`, `labelKey`, `payloadKey`, `minLength`, `debounceMs`).
- `FormFieldServerDefinition` — серверная конфигурация поля (`default`, `property`, `rules`, `filters`).
- enum-ы: `FormFieldTypesEnum`, `FormValueTypesEnum`, `FormIntervalTypesEnum`.

Пример поля с suggest:

```php
use PhpSoftBox\Forms\FormFieldTypesEnum;
use PhpSoftBox\Forms\DTO\FormFieldServerDefinition;
use PhpSoftBox\Forms\FormValueTypesEnum;
use PhpSoftBox\Validator\Filter\TrimFilter;
use PhpSoftBox\Validator\Rule\StringValidation;

new FormFieldDefinition(
    key: 'bank_name',
    label: 'Банк',
    fieldType: FormFieldTypesEnum::TEXT,
    valueType: FormValueTypesEnum::STRING,
    server: new FormFieldServerDefinition(
        rules: [(new StringValidation())->required()],
        filters: [new TrimFilter()],
    ),
    suggest: new FormSuggestDefinition(
        endpoint: '/api/requisites/suggest',
        queryParam: 'query',
        payloadKey: 'fields',
        minLength: 2,
    ),
);
```

## Рендереры

- `JsonFormRenderer`:

```php
$renderer = new JsonFormRenderer();
$payload = $renderer->render($formDefinition);
```

- `HtmlFormRenderer` (MVP-заглушка):

```php
$renderer = new HtmlFormRenderer();
$html = $renderer->render($formDefinition); // ''
```

## Валидация payload через форму

`Forms` добавляет базовый класс `AbstractFormDefinitionValidation`, который:
- читает `rules` и `filters` из `FormFieldServerDefinition`;
- применяет фильтры к payload;
- запускает `phpsoftbox/validator` на основе правил из формы.

`FormFieldServerDefinition::resolveProperty()` нужен, когда ключ поля формы не равен имени свойства/колонки,
куда вы сохраняете значение:

```php
$field = new FormFieldDefinition(
    key: 'profile.timezone',
    label: 'Часовой пояс',
    fieldType: FormFieldTypesEnum::SELECT,
    server: new FormFieldServerDefinition(
        property: 'timezone',
    ),
);

$target = $field->server?->resolveProperty($field->key); // 'timezone'
```

Если `property` не задан, метод возвращает исходный ключ поля.

```php
use PhpSoftBox\Forms\DTO\FormDefinition;
use PhpSoftBox\Forms\Validation\AbstractFormDefinitionValidation;

final class CompanyFormValidation extends AbstractFormDefinitionValidation
{
    protected function form(): FormDefinition
    {
        return CompanyForm::definition();
    }
}
```

## Дальше по плану

1. Добавить полноценный HTML-рендер.
2. Подключить `Forms` как source-of-truth для профилей `Requisites`.
