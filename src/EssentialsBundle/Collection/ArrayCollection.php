<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace EssentialsBundle\Collection;

use Closure;
use ArrayIterator;
use EssentialsBundle\Entity\EntityAbstract;

/**
 * An ArrayCollection is a InstanceCollection implementation that wraps a regular PHP array.
 *
 * @since  2.0
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author Jonathan Wage <jonwage@gmail.com>
 * @author Roman Borschel <roman@code-factory.org>
 */
class ArrayCollection extends EntityAbstract implements Collection
{
    /**
     * An array containing the entries of this collection.
     *
     * @var array
     */
    protected $elements = [];

    /**
     * @var int
     */
    private $count = 0;

    /**
     * Initializes a new ArrayCollection.
     *
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        $this->count = count($elements);
        $this->elements = $elements;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return json_decode(
            json_encode(
                $this->getObjectVarsRecursive($this->elements)
            ),
            true
        );
    }

    /**
     * {@inheritDoc}
     */
    public function first()
    {
        return reset($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function last()
    {
        return end($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return key($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        return next($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return current($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {
        if ((! isset($this->elements[$key]))
            && (! array_key_exists($key, $this->elements))
        ) {
            return null;
        }

        $removed = $this->elements[$key];
        unset($this->elements[$key]);

        $this->elements = array_map(function ($el) {
            return $el;
        }, $this->elements);

        $this->count--;

        return $removed;
    }

    /**
     * {@inheritDoc}
     */
    public function removeElement($element)
    {
        $key = array_search($element, $this->elements, true);

        if ($key === false) {
            return false;
        }

        $removed = $this->elements[$key];
        unset($this->elements[$key]);

        $this->elements = array_map(function ($el) {
            return $el;
        }, $this->elements);

        $this->count--;

        return $removed;
    }

    /**
     * @param $item
     * @return ArrayCollection
     */
    public function push($item): ArrayCollection
    {
        return $this->add($item);
    }

    public function pop()
    {
        return $this->remove(count($this)  - 1);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        if (! isset($offset)) {
            return $this->add($value);
        }

        return $this->set($offset, $value);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function containsKey($key): bool
    {
        return ((isset($this->elements[$key]))
            || (array_key_exists($key, $this->elements)));
    }

    /**
     * {@inheritDoc}
     */
    public function contains($element): bool
    {
        return (in_array($element, $this->elements, true));
    }

    /**
     * {@inheritDoc}
     */
    public function exists(\Closure $p): bool
    {
        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function indexOf($element)
    {
        return array_search($element, $this->elements, true);
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return isset($this->elements[$key]) ? $this->elements[$key] : null;
    }

    /**
     * {@inheritDoc}
     */
    public function getKeys()
    {
        return array_keys($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function getValues()
    {
        return array_values($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * {@inheritDoc}
     * @return ArrayCollection
     */
    public function set($key, $value): ArrayCollection
    {
        $this->elements[$key] = $value;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @return ArrayCollection
     */
    public function add($value): ArrayCollection
    {
        $this->count++;
        $this->elements[] = $value;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    /**
     * Required by interface IteratorAggregate.
     *
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function map(Closure $func)
    {
        return new static(array_map($func, $this->elements));
    }

    /**
     * {@inheritDoc}
     */
    public function filter(Closure $p)
    {
        return new static(array_filter($this->elements, $p));
    }

    /**
     * {@inheritDoc}
     */
    public function forAll(Closure $p): bool
    {
        foreach ($this->elements as $key => $element) {
            if (! $p($key, $element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function partition(Closure $p): array
    {
        $matches = $noMatches = [];

        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                $matches[$key] = $element;
            } else {
                $noMatches[$key] = $element;
            }
        }

        return [ new static($matches), new static($noMatches) ];
    }

    /**
     * Returns a string representation of this object.
     *
     * @return string
     */
    public function __toString(): string
    {
        return __CLASS__ . '@' . spl_object_hash($this);
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        unset($this->elements);

        $this->elements = [];
        $this->count = 0;
    }

    /**
     * {@inheritDoc}
     */
    public function slice($offset, $length = null): array
    {
        return array_slice($this->elements, $offset, $length, true);
    }

    /**
     * Sort based on a function
     * @param Closure $fn
     */
    public function usort(Closure $fn)
    {
        usort($this->elements, $fn);
    }
}
