<?php

namespace hipszkij\NovaButton;

use hipszkij\NovaButton\Events\ButtonClick;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Resource;

class Button extends Field
{
    public $component = 'nova-button';

    public $showOnUpdate = false;

    public $showOnCreation = false;

    public ?string $text = null;

    public ?string $key = null;

    public ?string $model = null;

    public array $config = [];

    public ?string $style = null;

    public ?string $loadingText = null;

    public ?string $loadingStyle = null;

    public ?string $successText = null;

    public ?string $successStyle = null;

    public ?string $errorText = null;

    public ?string $errorStyle = null;

    public ?array $confirm = null;

    public bool $reload = false;

    public string $event = ButtonClick::class;

    public ?string $action = null;

    public $visible = true;

    public bool $disabled = false;

    public ?string $title = null;

    public ?string $label = null;

    public ?string $indexName = null;

    public array $classes = [];

    public ?string $type = null;

    public ?array $route = null;

    public ?array $link = null;

    public string $indexAlign = 'left';

    public ?string $loadingClasses = null;

    public ?string $successClasses = null;

    public ?string $errorClasses = null;

    /**
     * Create a new field.
     *
     * @param string $name
     * @param string|callable|null $attribute
     */
    public function __construct($name, $attribute = null)
    {
        parent::__construct($name, $attribute);

        $this->text = $name;
        $this->key = $attribute ?? Str::kebab($name);
        $this->config = config('nova-button');
        $this->indexName = $name;

        $this->addDefaultSettings();
    }

    /**
     * Add default settings
     *
     * @return void
     */
    protected function addDefaultSettings(): void
    {
        $this->addLinkFallbacks();
        $this->style = Arr::get($this->config, 'defaults.style', 'link-primary');
        $this->loadingText = Arr::get($this->config, 'defaults.loadingText', 'Loading');
        $this->loadingStyle = Arr::get($this->config, 'defaults.loadingStyle', str_replace('primary', 'grey', $this->style));
        $this->errorText = Arr::get($this->config, 'defaults.errorText', 'Error!');
        $this->errorStyle = Arr::get($this->config, 'defaults.errorStyle', str_replace('primary', 'danger', $this->style));
        $this->successText = Arr::get($this->config, 'defaults.successText', 'Success!');
        $this->successStyle = Arr::get($this->config, 'defaults.successStyle', str_replace('primary', 'success', $this->style));

    }

    /**
     * Add link fallbacks
     *
     * @return void
     */
    protected function addLinkFallbacks(): void
    {
        if (!Arr::has($this->config, 'styles.link-primary')) {
            $this->config['styles']['link-primary'] = 'cursor-pointer inline-block text-gray-50 text-black font-bold';
        }

        if (!Arr::has($this->config, 'styles.link-success')) {
            $this->config['styles']['link-success'] = 'cursor-pointer inline-block text-green-500 font-bold';
        }

        if (!Arr::has($this->config, 'styles.link-grey')) {
            $this->config['styles']['link-grey'] = 'cursor-pointer inline-block text-grey-500 font-bold';
        }

        if (!Arr::has($this->config, 'styles.link-danger')) {
            $this->config['styles']['link-danger'] = 'cursor-pointer inline-block text-red-500 font-bold';
        }
    }

    /**
     * Resolve the field's value.
     *
     * @param mixed $resource
     * @param ?string $attribute
     * @return void
     */
    public function resolve($resource, $attribute = null): void
    {
        parent::resolve($resource, $attribute);

        $this->model = get_class($resource);
        $this->classes[] = 'nova-button-' . strtolower(class_basename($resource));
        $this->classes[] = Arr::get($this->config, "styles.$this->style");
        $this->loadingClasses = Arr::get($this->config, "styles.$this->loadingStyle");
        $this->successClasses = Arr::get($this->config, "styles.$this->successStyle");
        $this->errorClasses = Arr::get($this->config, "styles.$this->errorStyle");

        $this->withMeta([
            'text' => $this->text,
            'action' => $this->action,
            'key' => $this->key,
            'loadingText' => $this->loadingText,
            'model' => $this->model,
            'successText' => $this->successText,
            'errorText' => $this->errorText,
            'confirm' => $this->confirm,
            'reload' => $this->reload,
            'event' => $this->event,
            'visible' => $this->visible,
            'disabled' => $this->disabled,
            'title' => $this->title,
            'label' => $this->label,
            'indexName' => $this->indexName,
            'classes' => $this->uniqueClasses(),
            'type' => $this->type,
            'route' => $this->route,
            'link' => $this->link,
            'indexAlign' => $this->indexAlign,
            'loadingClasses' => $this->loadingClasses,
            'successClasses' => $this->successClasses,
            'errorClasses' => $this->errorClasses,
        ]);
    }

    /**
     * Enable the confirmation button type.
     *
     * @param ?string $message1
     * @param ?string $message2
     * @param ?string $cancelButtonText
     * @return $this
     */
    public function confirm(?string $message1 = null, ?string $message2 = null, ?string $cancelButtonText = null): self
    {
        $this->confirm = [
            'title' => __('Confirmation'),
            'body' => null,
            'cancelButtonText' => $cancelButtonText ?: __('Cancel'),
        ];

        if ($message1 !== null && $message2 === null) {
            $this->confirm['body'] = $message1;
        }

        if ($message1 !== null && $message2 !== null) {
            $this->confirm['title'] = $message1;
            $this->confirm['body'] = $message2;
        }

        return $this;
    }

    /**
     * Set the model class on which to trigger the action, use fully qualified name
     *
     * @param string|null $model
     * @return Button
     */
    public function modelForAction(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Set an action to trigger
     *
     * @param string|null $action
     * @return Button
     */
    public function action(?string $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Enable the reload button type.
     *
     * @param bool $reload
     * @return $this
     */
    public function reload(bool $reload = true): self
    {
        $this->reload = $reload;

        return $this;
    }

    /**
     * Enable the event button type.
     *
     * @param string $event
     * @return $this
     */
    public function event(string $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Set the button visibility.
     *
     * @param bool $condition
     * @return $this
     */
    public function visible(bool $condition): self
    {
        $this->visible = $condition;

        return $this;
    }

    /**
     * Set the button disabled state.
     *
     * @param bool $condition
     * @return $this
     */
    public function disabled(bool $condition = true): self
    {
        $this->disabled = $condition;

        return $this;
    }

    /**
     * Set the loading text.
     *
     * @param string $loadingText
     * @return $this
     */
    public function loadingText(string $loadingText): self
    {
        $this->loadingText = $loadingText;

        return $this;
    }

    /**
     * Set the success text.
     *
     * @param string $successText
     * @return $this
     */
    public function successText(string $successText): self
    {
        $this->successText = $successText;

        return $this;
    }

    /**
     * Set the error text.
     *
     * @param string $errorText
     * @return $this
     */
    public function errorText(string $errorText): self
    {
        $this->errorText = $errorText;

        return $this;
    }

    /**
     * Set the title.
     *
     * @param string $title
     * @return $this
     */
    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the label.
     *
     * @param string $label
     * @return $this
     */
    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the index name.
     *
     * @param ?string $indexName
     * @return $this
     */
    public function indexName(?string $indexName = null): self
    {
        $this->indexName = $indexName;

        return $this;
    }

    /**
     * Set the classes.
     *
     * @param ...$classes
     * @return $this
     */
    public function classes(...$classes): self
    {
        $this->classes = array_merge($this->classes, ...$classes);

        return $this;
    }

    /**
     * Unique css classes
     *
     * @return string
     */
    private function uniqueClasses(): string
    {
        $unique = [];
        foreach ($this->classes as $class) {
            if (!in_array($class, $unique)) {
                $unique[] = $class;
            }
        }

        return implode(' ', $unique);
    }

    /**
     * Set the style.
     *
     * @param string $style
     * @return $this
     */
    public function style(string $style): self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Set the loading style.
     *
     * @param string $loadingStyle
     * @return $this
     */
    public function loadingStyle(string $loadingStyle): self
    {
        $this->loadingStyle = $loadingStyle;

        return $this;
    }

    /**
     * Set the success style.
     *
     * @param string $successStyle
     * @return $this
     */
    public function successStyle(string $successStyle): self
    {
        $this->successStyle = $successStyle;

        return $this;
    }

    /**
     * Set the error style.
     *
     * @param string $errorStyle
     * @return $this
     */
    public function errorStyle(string $errorStyle): self
    {
        $this->errorStyle = $errorStyle;

        return $this;
    }

    /**
     * Set the index route.
     *
     * @param string $namespace
     * @return $this
     */
    public function index(string $namespace): self
    {
        $this->route('index', [
            'resourceName' => $this->normalizeResourceName($namespace),
        ]);

        return $this;
    }

    /**
     * Set the detail route.
     *
     * @param string $namespace
     * @param int $id
     * @return $this
     */
    public function detail(string $namespace, int $id): self
    {
        $this->route('detail', [
            'resourceName' => $this->normalizeResourceName($namespace),
            'resourceId' => $id,
        ]);

        return $this;
    }

    /**
     * Set the create route.
     *
     * @param string $namespace
     * @return $this
     */
    public function create(string $namespace): self
    {
        $this->route('create', [
            'resourceName' => $this->normalizeResourceName($namespace),
        ]);

        return $this;
    }

    /**
     * Set the edit route.
     *
     * @param string $namespace
     * @param int $id
     * @return $this
     */
    public function edit(string $namespace, int $id): self
    {
        $this->route('edit', [
            'resourceName' => $this->normalizeResourceName($namespace),
            'resourceId' => $id,
        ]);

        return $this;
    }

    /**
     * Set the lens route.
     *
     * @param string $namespace
     * @param string $key
     * @return $this
     */
    public function lens(string $namespace, string $key): self
    {
        $this->route('lens', [
            'resourceName' => $this->normalizeResourceName($namespace),
            'lens' => $key,
        ]);

        return $this;
    }

    /**
     * Set the link.
     *
     * @param string $href
     * @param string $target
     * @return $this
     */
    public function link(string $href, string $target = '_blank'): self
    {
        $this->type = 'link';
        $this->link = compact('href', 'target');

        return $this;
    }

    /**
     * Set the route.
     *
     * @param string $name
     * @param array $params
     * @return $this
     */
    protected function route(string $name, array $params = []): self
    {
        $this->type = 'route';
        $this->route = [
            'name' => $name,
            'params' => $params,
            'query' => [],
        ];

        return $this;
    }

    /**
     * Set the route params.
     *
     * @param array $params
     * @return $this
     */
    public function withParams(array $params): self
    {
        $this->route['query'] = array_merge($this->route['query'] ?? [], $params);

        return $this;
    }

    /**
     * Set the index filters.
     *
     * @param array $filters
     * @return $this
     */
    public function withFilters(array $filters): self
    {
        $resourceName = $this->route['params']['resourceName'] ?? null;

        if ($resourceName === null) {
            return $this;
        }

        $key = $resourceName . '_filter';

        $query = collect($filters)
            ->map(function ($value, $key) {
                return [
                    'class' => $key,
                    'value' => $value,
                ];
            })->values();

        $this->route['query'][$key] = base64_encode(json_encode($query));

        return $this;
    }

    /**
     * Normalize resourceName
     *
     * @param string $namespace
     * @return string
     */
    protected function normalizeResourceName(string $namespace): string
    {
        return class_exists($namespace) && is_subclass_of($namespace, Resource::class)
            ? $namespace::uriKey() : $namespace;
    }
}
