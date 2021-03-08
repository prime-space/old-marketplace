<?php namespace Well\DBBundle\Options;

/**
 * Isolation options
 */
final class IsolationOptions
{
    /** @const string Isolation level: READ UNCOMMITTED */
    const READ_UNCOMMITTED = 'READ UNCOMMITTED';

    /** @const string Isolation level: READ COMMITTED */
    const READ_COMMITTED = 'READ COMMITTED';

    /** @const string Isolation level: REPEATABLE READ */
    const REPEATABLE_READ = 'REPEATABLE READ';

    /** @const string Isolation level: SERIALIZABLE */
    const SERIALIZABLE = 'SERIALIZABLE';
}
