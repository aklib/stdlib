<?php
/**
 * FlorianWolters\Component\Core\StringEscapeUtils
 *
 * PHP Version 5.3
 *
 * @author    Florian Wolters <wolters.fl@gmail.com>
 * @copyright 2010-2014 Florian Wolters (http://blog.florianwolters.de)
 * @license   https://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @link      https://github.com/FlorianWolters/PHP-Component-Core-StringUtils
 */


/**
 * 
 * StringUtils.php
 * 
 * @since 01.06.2017
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */

namespace Aklib\Stdlib\Util;

/**
 * The class {@see StringEscapeUtils} class escapes and unescapes `string`s for
 * PHP, JavaScript, HTML, XML, and SQL.
 *
 * This class is inspired by the Java class {@link
 * https://commons.apache.org/proper/commons-lang/javadocs/api-3.1/org/apache/commons/lang3/StringEscapeUtils.html
 * StringEscapeUtils} from the {@link https://commons.apache.org/lang Apache Commons Lang
 * Application Programming Interface (API)}.
 *
 * @since Class available since Release 0.3.0
 */
final class StringEscapeUtils
{
    // @codeCoverageIgnoreStart

    /**
     * {@see RandomStringUtils} instances can **NOT** be constructed in standard
     * programming.
     *
     * Instead, the class should be used as:
     *
     * StringEscapeUtils::escapeHtml('foo');
     */
    protected function __construct()
    {
        // NOOP
    }

    // @codeCoverageIgnoreEnd

    /**
     * Escapes the characters in a `string` using HTML entities.
     *
     * For example,
     * `"bread" & "butter"`
     * becomes:
     * `&quot;bread&quot; &amp; &quot;butter&quot;`.
     *
     * Supports all known HTML 4.0 entities, including funky accents. Note that
     * the commonly used apostrophe escape character (`&apos;`) is not a legal
     * entity and so is not supported).
     *
     * @param string $str The `string` to escape.
     *
     * @return string The escaped `string` or `null` if `null` `string` input.
     * @see unescapeHtml
     */
    public static function escapeHtml($str)
    {
        return \htmlentities($str, \ENT_COMPAT, 'UTF-8', false);
    }

    /**
     * Unescapes a `string` containing entity escapes to a `string`
     * containing the actual Unicode characters corresponding to the escapes.
     *
     * Supports HTML 4.0 entities.
     * For example,
     * `'&lt;Fran&ccedil;ais&gt;'`
     * becomes
     * `'<Français>'`.
     *
     * If an entity is unrecognized, it is left alone, and inserted verbatim
     * into the esult `string`. e.g. `"&gt;&zzzz;x"` will become `">&zzzz;x"`.
     *
     * @param string $str The `string` to unescape.
     *
     * @return string The unescaped `string` or `null` if `null` `string` input.
     * @see escapeHtml
     */
    public static function unescapeHtml($str)
    {
        return \html_entity_decode($str, \ENT_COMPAT, 'UTF-8');
    }

    /**
     * Escapes the characters in a `string` to be suitable to pass to a
     * regular expression.
     *
     * @param string $str       The `string` to escape.
     * @param string $delimiter The regular expression delimiter.
     *
     * @return string The escaped `string` or `null` if `null` `string` input.
     */
    public static function escapeRegEx($str, $delimiter = '/')
    {
        return StringUtils::isEmpty($str)
            ? $str
            : \preg_quote($str, $delimiter);
    }

    /**
     * Escapes the characters in a `string` to be suitable to pass to a SQL
     * query.
     *
     * For example,
     *
     *     $title = StringEscapeUtils::escapeSql('McHale's Navy');
     *     $sth->query('SELECT * FROM movies WHERE title="' . $title . '"');
     *
     * At present, this method only turns single-quotes into doubled
     * single-quotes (`"McHale's Navy"` => `"McHale''s Navy"`). It does not
     * handle the cases of percent (`%`) or underscore (`_`) for use in `LIKE`
     * clauses.
     *
     * @param string $str The `string` to escape.
     *
     * @return string The esaped `string` or `null` if `null` `string` input.
     */
    public static function escapeSql($str)
    {
        return StringUtils::replace($str, '\'', '\'\'');
    }
}
