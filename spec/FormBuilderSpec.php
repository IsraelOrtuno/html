<?php

namespace spec\Styde\Html;

use Styde\Html\Theme;
use Styde\Html\HtmlBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument as Arg;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory;

class FormBuilderSpec extends ObjectBehavior
{
    function let(UrlGenerator $url, Theme $theme, Factory $view)
    {
        $theme->getView()->shouldBeCalled()->willReturn($view);

        $this->beConstructedWith($url, 'csrf_token', $theme);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\FormBuilder');
    }

    function it_adds_the_novalidate_attribute_to_all_forms($html)
    {
        $this->novalidate(true);

        $this->open(['method' => 'GET'])->shouldReturn('<form method="GET" novalidate>');
    }

    function it_generates_time_inputs($html)
    {
        $this->time('time')->render()->shouldReturn('<input type="time" name="time" value="">');
    }

    function it_generate_radios($theme)
    {
        // Having
        $name = 'gender';
        $attributes = [];

        // Expect
        $radios = [
            [
                "name" => "gender",
                "value" => "m",
                "label" => "Male",
                "selected" => true,
                "id" => "gender_m"
            ],
            [
                "name" => "gender",
                "value" => "f",
                "label" => "Female",
                "selected" => false,
                "id" => "gender_f"
            ]
        ];

        $theme->getView()->shouldBeCalled();
        $theme->render(null, compact('name', 'radios', 'attributes'), "forms.radios")->shouldBeCalled();

        // When
        $this->radios('gender', ['m' => 'Male', 'f' => 'Female'], 'm');
    }

    function it_generate_checkboxes($theme)
    {
        // Having
        $name = 'tags';
        $tags = ['php' => 'PHP', 'python' => 'Python', 'js' => 'JS', 'ruby' => 'Ruby on Rails'];
        $checked = ['php', 'js'];
        $attributes = [];

        // Expect
        $checkboxes = [
            [
                "name" => "tags[]",
                "value" => "php",
                "label" => "PHP",
                "checked" => true,
                "id" => "tags_php"
            ],
            [
                "name" => "tags[]",
                "value" => "python",
                "label" => "Python",
                "checked" => false,
                "id" => "tags_python"
            ],
            [
                "name" => "tags[]",
                "value" => "js",
                "label" => "JS",
                "checked" => true,
                "id" => "tags_js"
            ],
            [
                "name" => "tags[]",
                "value" => "ruby",
                "label" => "Ruby on Rails",
                "checked" => false,
                "id" => "tags_ruby"
            ]
        ];
        $theme->getView()->shouldBeCalled();
        $theme->render(null, compact('name', 'checkboxes', 'attributes'), "forms.checkboxes")->shouldBeCalled();

        // When
        $this->checkboxes('tags', $tags, $checked);
    }

}
