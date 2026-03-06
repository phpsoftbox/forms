# PhpSoftBox Forms

Компонент для декларативного описания форм и рендеринга в разные драйверы.

## Установка

```bash
composer require phpsoftbox/forms
```

## Что есть сейчас

- DTO формы: `FormDefinition`, `FormFieldDefinition`, `FormSuggestDefinition`
- Enum типов поля: `FormFieldTypesEnum`
- Server DTO поля: `FormFieldServerDefinition`
- Enum типов значения: `FormValueTypesEnum`, `FormIntervalTypesEnum`
- JSON-рендерер: `JsonFormRenderer`
- HTML-рендерер: `HtmlFormRenderer` (пока заглушка)
- Базовая валидация payload: `Validation/AbstractFormDefinitionValidation`

## Документация

- [docs/01-usage.md](docs/01-usage.md)
