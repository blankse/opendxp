# HeadTitle Templating Extension

> The HeadTitle templating extension extends [Placeholder Templating Extension](./00_Placeholder.md)

The HTML `<title>` element is used to provide a title for an HTML document. 
The HeadTitle helper allows you to programmatically create and store the title for later retrieval and output.

### Basic Usage

You may specify a title tag at any time. 
A typical usage would have you setting title segments for each level of depth in your application: site, 
controller, action, and potentially resource.


```twig
{% do opendxp_head_title('My first part') %}
{% do opendxp_head_title('The 2nd part') %}

{# setting the site in the title; possibly in the layout script: #}
{% do opendxp_head_title('My OpenDXP Website') %}

{# setting a separator string for segments: #}
{% do opendxp_head_title().setSeparator(' / ') %}
```

When you're finally ready to render the title in your layout script, simply echo the helper:

```twig
{{ opendxp_head_title() }}
{# renders My first part / The 2nd part / My OpenDXP Website #}
```
