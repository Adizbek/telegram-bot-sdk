<?php
namespace Telegram\Bot\Methods;

use Telegram\Bot\Objects\Update;
use Telegram\Bot\Events\UpdateWasReceived;

/**
 * Class GetUpdates
 *
 * Use this method to receive incoming updates using long polling.
 *
 * <code>
 * $params = [
 *   'offset'  => '',
 *   'limit'   => '',
 *   'timeout' => '',
 * ];
 * </code>
 *
 * @link https://core.telegram.org/bots/api#getupdates
 *
 * @param array  $params
 * @param bool   $shouldEmitEvents
 *
 * @var int|null $params ['offset']
 * @var int|null $params ['limit']
 * @var int|null $params ['timeout']
 *
 * @method GetUpdates offset($offset = null) int|null
 * @method GetUpdates limit($limit = null) int|null
 * @method GetUpdates timeout($timeout = null) int|null
 *
 * @method Update[] getResult($dumpAndDie = false)
 * @method Update[] go($dumpAndDie = false) Alias for getResult().
 */
class GetUpdates extends Method
{
    /** @var bool Should Emit Events */
    protected $shouldEmitEvents = true;

    /**
     * @param bool $shouldEmitEvents
     *
     * @return $this
     */
    public function shouldEmitEvents($shouldEmitEvents)
    {
        $this->shouldEmitEvents = $shouldEmitEvents;

        return $this;
    }

    /** {@inheritdoc} */
    protected function returns()
    {
        return collect($this->factory->response()->getResult())
            ->map(function ($data) {

                $update = new Update($data);

                if ($this->shouldEmitEvents) {
                    $this->factory->getTelegram()->emitEvent(
                        new UpdateWasReceived($update, $this->factory->getTelegram())
                    );
                }

                return $update;
            })
            ->all();
    }
}