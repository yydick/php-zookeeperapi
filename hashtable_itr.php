<?php
/************************************************
* 本程序于2018年03月25日
* 由陈浩波编写完成
* 任何人使用时请保留该声明
*/
namespace SPOOL\ZOOKEEPER\CLIENT;

include_once 'zookeeper.jute.php';

function hashtable_iterator(hashtable &$h) : hashtable_itr
{
    $i = $tablelength = 0;
    $itr = new hashtable_itr();
    $itr->h = $h;
    $itr->e = NULL;
    $itr->parent = NULL;
    $tablelength = $h->tablelength;
    $itr->index = $tablelength;
    if (0 == $h->entrycount) return $itr;

    for ($i = 0; $i < tablelength; $i++)
    {
        if (NULL != $h->table[i])
        {
            $itr->e = $h->table[i];
            $itr->index = $i;
            break;
        }
    }
    return $itr;
}

/*****************************************************************************/
/* advance - advance the iterator to the next element
 *           returns zero if advanced to end of table */
 
function hashtable_iterator_advance(hashtable_itr &$itr) : int
{
//    unsigned int j,tablelength;
//    struct entry **table;
//    struct entry *next;
    $j = $tablelength = 0;
    $table = $next = new entry();
    if (NULL == $itr->e) return 0; /* stupidity check */

    $next = $itr->e->next();
    if (NULL != $next)
    {
        $itr->parent = $itr->e;
        $itr->e = $next;
        return -1;
    }
    $tablelength = $itr->h->tablelength;
    $itr->parent = NULL;
    if ($tablelength <= ($j = ++$itr->index))
    {
        $itr->e = NULL;
        return 0;
    }
    $table = $itr->h->table;
    while (NULL == ($next = $table[j]))
    {
        if (++$j >= $tablelength)
        {
            $itr->index = $tablelength;
            $itr->e = NULL;
            return 0;
        }
    }
    $itr->index = $j;
    $itr->e = $next;
    return -1;
}

/*****************************************************************************/
/* remove - remove the entry at the current iterator position
 *          and advance the iterator, if there is a successive
 *          element.
 *          If you want the value, read it before you remove:
 *          beware memory leaks if you don't.
 *          Returns zero if end of iteration. */

function hashtable_iterator_remove(hashtable_itr &$itr) : int
{
//    struct entry *remember_e, *remember_parent;
//    int ret;
    $ret = 0;
    $remember_e = $remember_parent = new entry();
    /* Do the removal */
    if (NULL == ($itr->parent))
    {
        /* element is head of a chain */
        $itr->h->table[$itr->index] = $itr->e->next();
    } else {
        /* element is mid-chain */
        $itr->parent = &$itr->e;
    }
    /* itr->e is now outside the hashtable */
    $remember_e = &$itr->e;
    $itr->h->entrycount--;
    unset($remember_e->k);
//    freekey(remember_e->k);

    /* Advance the iterator, correcting the parent */
    $remember_parent = &$itr->parent;
    $ret = hashtable_iterator_advance($itr);
    if ($itr->parent == $remember_e) { $itr->parent = $remember_parent; }
    unset($remember_e);
    return $ret;
}

/*****************************************************************************/
/* returns zero if not found */
function hashtable_iterator_search(hashtable_itr &$itr, hashtable &$h, string &$k) : int
{
//    struct entry *e, *parent;
//    unsigned int hashvalue, index;
    $e = new entry();
    $parent = new entry();
    $hashvalue = $index = 0;
    $hashvalue = hash($h, $k);
    $index = $indexFor($h->tablelength, $hashvalue);

    $e = $h->table[$index];
    $parent = NULL;
    while ($e->valid())
    {
        /* Check hash value to short circuit heavier comparison */
        if (($hashvalue == $e->h) && ($h->eqfn($k, $e->k)))
        {
            $itr->index = $index;
            $itr->e = $e;
            $itr->parent = $parent;
            $itr->h = $h;
            return -1;
        }
        $parent = $e;
        $e->next();
    }
    return 0;
}

