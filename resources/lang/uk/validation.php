<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Поле :attribute повинно бути прийняте.',
    'accepted_if' => 'Поле :attribute повинно бути прийняте, коли :other є :value.',
    'active_url' => 'Поле :attribute не є дійсною URL-адресою.',
    'after' => 'Поле :attribute повинно бути датою після :date.',
    'after_or_equal' => 'Поле :attribute повинно бути датою після або рівною :date.',
    'alpha' => 'Поле :attribute повинно містити лише букви.',
    'alpha_dash' => 'Поле :attribute може містити лише букви, цифри, дефіси та підкреслення.',
    'alpha_num' => 'Поле :attribute може містити лише букви та цифри.',
    'array' => 'Поле :attribute повинно бути масивом.',
    'before' => 'Поле :attribute повинно бути датою до :date.',
    'before_or_equal' => 'Поле :attribute повинно бути датою до або рівною :date.',
    'between' => [
        'numeric' => 'Поле :attribute повинно бути між :min та :max.',
        'file' => 'Поле :attribute повинно бути між :min та :max кілобайт.',
        'string' => 'Поле :attribute повинно бути між :min та :max символами.',
        'array' => 'Поле :attribute повинно містити від :min до :max елементів.',
    ],
    'boolean' => 'Поле :attribute повинно мати значення true або false.',
    'confirmed' => 'Поле :attribute не збігається з підтвердженням.',
    'current_password' => 'Поточний пароль невірний.',
    'date' => 'Поле :attribute не є дійсною датою.',
    'date_equals' => 'Поле :attribute повинно бути датою, рівною :date.',
    'date_format' => 'Поле :attribute не відповідає формату :format.',
    'declined' => 'Поле :attribute повинно бути відхилене.',
    'declined_if' => 'Поле :attribute повинно бути відхилене, коли :other є :value.',
    'different' => 'Поля :attribute і :other повинні бути різними.',
    'digits' => 'Поле :attribute повинно мати :digits цифр.',
    'digits_between' => 'Поле :attribute повинно мати від :min до :max цифр.',
    'dimensions' => 'Поле :attribute має недійсні розміри зображення.',
    'distinct' => 'Поле :attribute має повторюване значення.',
    'email' => 'Поле :attribute повинно бути дійсною адресою електронної пошти.',
    'ends_with' => 'Поле :attribute повинно закінчуватися одним із наступних значень: :values.',
    'enum' => 'Обране значення для :attribute недійсне.',
    'exists' => 'Обране значення для :attribute недійсне.',
    'file' => 'Поле :attribute повинно бути файлом.',
    'filled' => 'Поле :attribute повинно мати значення.',
    'gt' => [
        'numeric' => 'Поле :attribute повинно бути більше :value.',
        'file' => 'Поле :attribute повинно бути більше :value кілобайт.',
        'string' => 'Поле :attribute повинно бути більше :value символів.',
        'array' => 'Поле :attribute повинно містити більше :value елементів.',
    ],
    'gte' => [
        'numeric' => 'Поле :attribute повинно бути більше або рівне :value.',
        'file' => 'Поле :attribute повинно бути більше або рівне :value кілобайт.',
        'string' => 'Поле :attribute повинно бути більше або рівне :value символів.',
        'array' => 'Поле :attribute повинно містити :value елементів або більше.',
    ],
    'image' => 'Поле :attribute повинно бути зображенням.',
    'in' => 'Обране значення для :attribute недійсне.',
    'in_array' => 'Поле :attribute не існує в :other.',
    'integer' => 'Поле :attribute повинно бути цілим числом.',
    'ip' => 'Поле :attribute повинно бути дійсною IP-адресою.',
    'ipv4' => 'Поле :attribute повинно бути дійсною IPv4-адресою.',
    'ipv6' => 'Поле :attribute повинно бути дійсною IPv6-адресою.',
    'json' => 'Поле :attribute повинно бути дійсним JSON-рядком.',
    'lt' => [
        'numeric' => 'Поле :attribute повинно бути менше :value.',
        'file' => 'Поле :attribute повинно бути менше :value кілобайт.',
        'string' => 'Поле :attribute повинно бути менше :value символів.',
        'array' => 'Поле :attribute повинно містити менше :value елементів.',
    ],
    'lte' => [
        'numeric' => 'Поле :attribute повинно бути менше або рівне :value.',
        'file' => 'Поле :attribute повинно бути менше або рівне :value кілобайт.',
        'string' => 'Поле :attribute повинно бути менше або рівне :value символів.',
        'array' => 'Поле :attribute повинно містити не більше :value елементів.',
    ],
    'mac_address' => 'Поле :attribute повинно бути дійсною MAC-адресою.',
    'max' => [
        'numeric' => 'Поле :attribute не повинно бути більше :max.',
        'file' => 'Поле :attribute не повинно бути більше :max кілобайт.',
        'string' => 'Поле :attribute не повинно бути більше :max символів.',
        'array' => 'Поле :attribute не повинно містити більше :max елементів.',
    ],
    'mimes' => 'Поле :attribute повинно бути файлом типу: :values.',
    'mimetypes' => 'Поле :attribute повинно бути файлом типу: :values.',
    'min' => [
        'numeric' => 'Поле :attribute повинно бути принаймні :min.',
        'file' => 'Поле :attribute повинно бути принаймні :min кілобайт.',
        'string' => 'Поле :attribute повинно бути принаймні :min символів.',
        'array' => 'Поле :attribute повинно містити принаймні :min елементів.',
    ],
    'multiple_of' => 'Поле :attribute повинно бути кратним :value.',
    'not_in' => 'Обране значення для :attribute недійсне.',
    'not_regex' => 'Формат поля :attribute недійсний.',
    'numeric' => 'Поле :attribute повинно бути числом.',
    'password' => 'Пароль невірний.',
    'present' => 'Поле :attribute повинно бути присутнім.',
    'prohibited' => 'Поле :attribute заборонено.',
    'prohibited_if' => 'Поле :attribute заборонено, коли :other є :value.',
    'prohibited_unless' => 'Поле :attribute заборонено, якщо :other не знаходиться серед :values.',
    'prohibits' => 'Поле :attribute забороняє значення :other.',
    'regex' => 'Формат поля :attribute недійсний.',
    'required' => 'Поле :attribute обов\'язкове для заповнення.',
    'required_array_keys' => 'Ключі у полі :attribute обов\'язкові: :values.',
    'required_if' => 'Поле :attribute обов\'язкове, коли :other є :value.',
    'required_unless' => 'Поле :attribute обов\'язкове, якщо :other не знаходиться серед :values.',
    'required_with' => 'Поле :attribute обов\'язкове, коли присутній :values.',
    'required_with_all' => 'Поле :attribute обов\'язкове, коли всі :values присутні.',
    'required_without' => 'Поле :attribute обов\'язкове, коли відсутні :values.',
    'required_without_all' => 'Поле :attribute обов\'язкове, коли жоден з :values не присутній.',
    'same' => 'Поля :attribute і :other повинні збігатися.',
    'size' => [
        'numeric' => 'Поле :attribute повинно бути розміром :size.',
        'file' => 'Поле :attribute повинно бути розміром :size кілобайт.',
        'string' => 'Поле :attribute повинно бути розміром :size символів.',
        'array' => 'Поле :attribute повинно містити :size елементів.',
    ],
    'starts_with' => 'Поле :attribute повинно починатися з одного з наступних значень: :values.',
    'string' => 'Поле :attribute повинно бути рядком.',
    'timezone' => 'Поле :attribute повинно бути дійсною часовою зоною.',
    'unique' => 'Таке значення поля :attribute вже зайняте.',
    'uploaded' => 'Не вдалося завантажити файл поля :attribute.',
    'url' => 'Поле :attribute має недійсний формат URL.',
    'uuid' => 'Поле :attribute має недійсний формат UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'test_not_found' => 'Тест не знайдено!',
        'question_not_found' => 'Питання не знайдено!',
        'answer_not_found' => 'Відповідь не знайдено!',
        'favorite_not_found' => 'Улюблене не найдено!'
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];